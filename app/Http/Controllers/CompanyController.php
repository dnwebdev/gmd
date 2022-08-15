<?php

namespace App\Http\Controllers;

use App\Enums\ManualTransferStatus;
use App\Http\Requests\CompanyProfileFormRequest;
use App\Mail\Bank\BankAccountChangeRequestMail;
use App\Models\AdditionalOrder;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\ListPayment;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAgent;
use App\Models\WithdrawRequest;
use App\Scopes\AudienceProviderGeneralScope;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use Kayiz\Woowa;
use Storage;

class CompanyController extends Controller
{


    var $my_company = 0;

    /**
     * __construct
     *
     * @param mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        toastr();
        $this->initalize($request);
        $upcoming_book = \App\Models\Order::where([
            'id_company' => $this->company,
            'status' => 0,
            'booking_type' => 'online'
        ])->whereHas('order_detail', function ($o) {
            $o->where('schedule_date', '>=', Carbon::now()->toDateString());
        })->orderBy('created_at', 'desc')->skip(0)->take(5)->get();

        $recent_order = \App\Models\Order::where([
            'id_company' => $this->company,
            'status' => 1,
            'booking_type' => 'online'
        ])->whereHas('order_detail', function ($q) {
            $q->whereRaw(" `schedule_date` between '" . date('Y-m-1') . "' and  '" . date('Y-m-t') . "' ");
        })->orderBy('created_at', 'desc')->skip(0)->take(5)->get();
        $topViewedProduct = Product::where([
            'booking_type' => 'online',
            'id_company' => $this->company
        ])
            //->availableQuota()
            ->has('schedule')
            ->whereHas('schedule', function ($query) {
                return $query->whereDate('end_date', '>=', today()->toDateString());
            })
            ->whereHas('order_detail', function ($od) {
                $od->whereHas('invoice', function ($order) {
                    $order->where('status', 1);
                });
            })
            ->withCount('order_detail')
            ->orderBy('order_detail_count', 'desc')
            ->orderBy('viewed', 'desc')
            ->having('order_detail_count', '>', 0)
            ->limit(5)
            ->get();
        $totalViewedProduct = Product::where('id_company', $this->company)->sum('viewed');
        $latestBlogs = BlogPost::withGlobalScope('provider',
            new AudienceProviderGeneralScope())->publish()->blog()->latest()->take(4)->get();
//        dd($request->get('klhk'));
        if (auth()->user()->company->is_klhk == 1) {
//            dd($request->get('klhk'));
            return view('klhk.dashboard.company.dashboard', [
                'upcoming_book' => $upcoming_book,
                'recent_order' => $recent_order,
                'topViewed' => $topViewedProduct,
                'totalViewed' => $totalViewedProduct
            ]);
        }
        return view('dashboard.company.dashboard',
            [
                'latest_blogs' => $latestBlogs,
                'upcoming_book' => $upcoming_book,
                'recent_order' => $recent_order,
                'topViewed' => $topViewedProduct,
                'totalViewed' => $totalViewedProduct
            ]);
    }

    /**
     * Function initalize get data user
     *
     * @param mixed $request
     *
     * @return void
     */
    private function initalize(Request $request)
    {
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * see profile company
     *
     * @param mixed $request
     *
     * @return void
     */
    public function my_profile(Request $request)
    {

        $this->initalize($request);
        $instance = \App\Models\Company::where('id_company', $this->company)->with('bank')->first();
        $list_payment = $instance->payments()->where('code_payment', 'bca_manual')->first();
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.profile.index', ['company' => $instance, 'list_payment' => $list_payment]);
        }
        return view('dashboard.company.profile.index', ['company' => $instance, 'list_payment' => $list_payment]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function gxp(Request $request)
    {

        $this->initalize($request);
        $instance = \App\Models\Company::find($this->company);
        if (auth()->user()->company->is_klhk == 1) {
            return view('dashboard.company.gxp.index', ['company' => $instance]);
        }
        return view('dashboard.company.gxp.index', ['company' => $instance]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function update(CompanyProfileFormRequest $request)
    {
        $this->initalize($request);
        $instance = \App\Models\Company::find($this->company);
        $bank = $instance->bank;
        $transfer = $instance->transfer_manual;
        $rule = [
            'bank' => 'required',
            'bank_account_name' => 'required|max:50',
            'bank_account_number' => 'required|min:5|max:25'
        ];
        $attributes = [];
        if (checkRequestExists($request, 'bank_account_name') || checkRequestExists($request, 'bank_account_number')):
            if (!$bank) {
                $rule['bank_account_document'] = 'nullable|image';
            } else {
                if (!$request->get('email_company')) {
                    if (empty(auth('web')->user()->email)) {
                        return apiResponse('403', 'Anda belum mengisi email, silahkan mengisi di pengaturan');
                    }
                }
                if ($bank->bank !== $request->input('bank') || $bank->bank_account_name !== $request->input('bank_account_name') || $bank->bank_account_number !== $request->input('bank_account_number')) {
                    $rule['bank_account_document'] = 'required|image';
                } elseif ($request->hasFile('bank_account_document')) {
                    $rule['bank_account_document'] = 'required|image';
                }
            }

            $attributes = [
                'bank' => strtolower(trans('bank_provider.select_bank')),
                'bank_account_name' => strtolower(trans('bank_provider.account_name')),
                'bank_account_number' => strtolower(trans('bank_provider.account_number')),
                'bank_account_document' => strtolower(trans('bank_provider.bank_document'))
            ];

        endif;
        if ($request->get('allow_bca')) {
            $paymentlist = ListPayment::where('code_payment', $request->get('allow_bca'))->first();

            if (empty($paymentlist)) {
                return apiResponse('403', 'Payment Not found');
            }
            $rule = [
                'name_rekening' => 'required|max:50',
                'no_rekening' => 'required|min:5|max:25'
            ];

            if (!$transfer) {
                $rule['document_upload'] = 'nullable|image';
            } else {
                if ($transfer->name_rekening !== $request->input('name_rekening') || $transfer->no_rekening !== $request->input('no_rekening')) {
                    if ($request->get('use_data')) {
                        $rule['upload_document'] = 'nullable|image';
                    } else {
                        $rule['upload_document'] = 'required|image';
                    }
                } elseif ($request->hasFile('upload_document')) {
                    if ($request->get('use_data')) {
                        $rule['upload_document'] = 'nullable|image';
                    } else {
                        $rule['upload_document'] = 'required|image';
                    }
                }
            }

            $attributes = [
                'name_rekening' => strtolower(trans('bank_provider.account_name')),
                'no_rekening' => strtolower(trans('bank_provider.account_number')),
                'upload_document' => strtolower(trans('bank_provider.bank_document'))
            ];

        }
        $this->validate($request, $rule, [], $attributes);

        try {
            \DB::beginTransaction();
            $notifWidget = false;
            $instance->lat = $request->get('lat');
            $instance->long = $request->get('long');
            if ($instance->google_place_id != $request->get('google_place_id')) {
                $instance->google_place_id = $request->get('google_place_id');
                $notifWidget = true;
            }

            // Masih kepakai
            // foreach (ListPayment::where('status', 1)->get() as $item) {
            //     $instance->payments()->updateExistingPivot($item->id, ['charge_to' => 0]);
            //     if ($request->has('listpayment') && count($request->listpayment) > 0) {
            //         if (in_array($item->id, $request->listpayment)) {
            //             $instance->payments()->updateExistingPivot($item->id, ['charge_to' => 1]);
            //         }
            //     }
            // }
//            if ($request->has('listpayment') && $request->listpayment->count() > 0){
//            }

            if ($request->get('company_name')) {
                $instance->company_name = strip_tags($request->get('company_name'));
            }


            if ($request->get('domain_name')) {
                $instance->domain = $request->get('domain_name');
            }

            if ($request->get('domain_memoria')) {
                $instance->domain_memoria = $request->get('domain_memoria');
            }

            if ($request->get('email_company')) {
                $instance->email_company = $request->get('email_company');
                if (!UserAgent::whereEmail($request->get('email_company'))->first() && $instance->agent->email == null) {
                    $instance->agent->update(['email' => $request->get('email_company')]);
                }
            }

            if ($request->get('phone_company')) {
                $instance->phone_company = $request->get('phone_company');

            }

            if ($request->get('city')) {
                $instance->id_city = $request->get('city');
            }

            if ($request->get('address_company')) {
                $instance->address_company = strip_tags($request->get('address_company'));
            }

            if ($request->get('about')) {
                $instance->about = clean($request->get('about'), 'simple');
            }

//        if($request->get('twitter_company')){
            $instance->twitter_company = $request->get('twitter_company');
//        }
//
//        if($request->get('facebook_company')){
            $instance->facebook_company = $request->get('facebook_company');
//        }

//        if($request->get('color_company')){
//            $instance->color_company = $request->get('color_company');
//        }
//
//        if($request->get('font_color_company')){
//            $instance->font_color_company = $request->get('font_color_company');
//        }
//
//            if ($request->get('short_description')) {
            $instance->short_description = strip_tags($request->get('short_description'));
//            }

            $instance->color_company = '0893CF';
            $instance->font_color_company = 'FFFFFF';
            $instance->title = $request->input('title');
            $instance->address_company = $request->input('address_company');
            $instance->description = $request->input('description');
            if (checkRequestExists($request, 'keywords', 'POST')) {
                $instance->keywords = implode(',', $request->input('keywords'));
                // $instance->keywords = $request->input('keywords');
            } else {
                $instance->keywords = '';
            }


            if ($request->get('quote')) {
                $instance->quote = $request->get('quote');
            }
            if ($request->has('password') && $request->password !== '' && $request->password !== null) {
                $user = \auth('web')->user();
                if (!\Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'message' => 'Wrong Password',
                        'errors' => ['old_password' => ['Wrong password']]
                    ], 422);
                }
                $user->password = bcrypt($request->password);
                $user->save();
            }


            if ($request->hasFile('logo')) {
                //Delete existing
                if ($instance->logo) {
                    Storage::disk('uploads')->delete('company_logo/' . $instance->logo);

                }

                $image_name = Str::slug('Logo Company ' . generateRandomString(12)) . '.' . $request->file('logo')->getClientOriginalExtension();

                $logo_sizes = [
                    [
                        'width' => 100,
                        'height' => 100,
                        'path' => public_path('uploads/company_logo')
                    ],
                    [
                        'width' => 32,
                        'height' => 32,
                        'path' => public_path('uploads/company_logo/favicon')
                    ]
                ];

                foreach ($logo_sizes as $size) {
                    File::isDirectory($size['path']) or File::makeDirectory($size['path'], 0777, true, true);
                    $rotate = (int)$request->input('crop-logo.rotate');
                    $img = Image::make($request->file('logo'));
                    if ($rotate !== 0):
                        $img->rotate(360 - $rotate);
                    endif;
                    $img->crop((int)$request->input('crop-logo.width'), (int)$request->input('crop-logo.height'),
                        (int)$request->input('crop-logo.x'), (int)$request->input('crop-logo.y'))
                        ->resize($size['width'], $size['height'], function ($constraint) {
                            return $constraint->aspectRatio();
                        })
                        ->save($size['path'] . '/' . $image_name)
                        ->destroy();
                }

                $instance->logo = $image_name;

            } elseif ($request->get('default-logo')) {
                $instance->logo = $request->get('default-logo');
            }

            if (!empty($request->hasFile('banner'))) {
                //Delete existing
                if ($instance->banner) {
                    Storage::disk('uploads')->delete('banners/' . $instance->banner);
                }

                $path = public_path('uploads/banners');
                $image_name = pathinfo($request->file('banner')->hashName(), PATHINFO_FILENAME) . '.jpg';
                // Buat folder jika tidak ada
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                $rotate = (int)$request->input('crop-banner.rotate');
                $img = Image::make($request->file('banner'));
                if ($rotate !== 0):
                    $img->rotate(360 - $rotate);
                endif;
                $img->crop((int)$request->input('crop-banner.width'), (int)$request->input('crop-banner.height'),
                    (int)$request->input('crop-banner.x'), (int)$request->input('crop-banner.y'))
                    ->resize(1600, 900, function ($constraint) {
                        return $constraint->aspectRatio();
                    })
                    ->encode('jpeg')
                    ->save($path . '/' . $image_name)
                    ->destroy();

                $instance->banner = $image_name;

            } elseif ($request->get('default-banner')) {
                $instance->banner = $request->get('default-banner');
            }

            if (checkRequestExists($request, 'bank_account_name') || checkRequestExists($request, 'bank_account_number')):
                if ($bank) {
                    if ($bank->bank_account_number != $request->input('bank_account_number') || $bank->bank_account_name != $request->get('bank_account_name') || $request->hasFile('bank_account_document')) {
                        if ($request->hasFile('bank_account_document')) {

                            $path = public_path('uploads/bank_document');
                            $image_name = pathinfo($request->file('bank_account_document')->hashName(),
                                    PATHINFO_FILENAME) . '.jpg';
                            // Buat folder jika tidak ada
                            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                            $rotate = (int)$request->input('crop-bank_account_document.rotate');
                            $img = Image::make($request->file('bank_account_document'));
                            if ($rotate !== 0):
                                $img->rotate(360 - $rotate);
                            endif;

                            $img->crop((int)$request->input('crop-bank_account_document.width'),
                                (int)$request->input('crop-bank_account_document.height'),
                                (int)$request->input('crop-bank_account_document.x'),
                                (int)$request->input('crop-bank_account_document.y'))
                                ->resize(800, 450, function ($constraint) {
                                    return $constraint->aspectRatio();
                                })
                                ->encode('jpeg')
                                ->save($path . '/' . $image_name)
                                ->destroy();

                            $requestbank = $bank->request_changes()->create([
                                'bank' => $request->get('bank'),
                                'bank_account_name' => $request->get('bank_account_name'),
                                'bank_account_number' => $request->get('bank_account_number'),
                                'bank_account_document' => $image_name,
                                'id_user' => auth('web')->id(),
                                'token' => generateRandomString(16),
                                'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                            ]);
                            $company = Company::find(auth()->user()->id_company);
                            $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                            if ($user->email) {
                                \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
                            }
                            $bankAtas = $image_name;


                        } else {
                            $requestbank = $bank->request_changes()->create([
                                'bank' => $request->get('bank'),
                                'bank_account_name' => $request->get('bank_account_name'),
                                'bank_account_number' => $request->get('bank_account_number'),
                                'id_user' => auth('web')->id(),
                                'token' => generateRandomString(16),
                                'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                            ]);
                            $company = Company::find(auth()->user()->id_company);
                            $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
//                            if ($user->email) {
//                                \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
//                            }
                            if ($user->email):
                                \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
                            elseif ($user->phone):
                                $message[] = 'Permintaan Penggantian Bank Account';
                                $message[] = 'Data perubahan :';
                                $message[] = 'Bank : ' . $request->get('bank');
                                $message[] = 'Nomor Akun Bank : ' . $request->get('bank_account_number');
                                $message[] = 'Nama Pemilik Rekening : ' . $request->get('bank_account_name');
                                $message[] = 'Tolak : ' . (request()->isSecure() ? 'https://' : 'http://') . $user->company->domain_memoria . '/change-bank-request?u=' . $requestbank->id . '&t=' . $requestbank->token . '&action=reject';
                                $message[] = 'Setujui : ' . (request()->isSecure() ? 'https://' : 'http://') . $user->company->domain_memoria . '/change-bank-request?u=' . $requestbank->id . '&t=' . $requestbank->token . '&action=approve';
                                Woowa::SendMessageAsync()->setPhone($user->phone_code . $user->phone)->setMessage(sprintf('%s', implode('\n', $message)))->prepareContent()->send();
                            endif;

                        }
                    }
                } else {
                    $image_name = null;
                    if ($request->hasFile('bank_account_document')) {
                        $path = public_path('uploads/bank_document');
                        $image_name = pathinfo($request->file('bank_account_document')->hashName(),
                                PATHINFO_FILENAME) . '.jpg';
                        // Buat folder jika tidak ada
                        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

                        $rotate = (int)$request->input('crop-bank_account_document.rotate');
                        $img = Image::make($request->file('bank_account_document'));
                        if ($rotate !== 0):
                            $img->rotate(360 - $rotate);
                        endif;

                        $img->crop((int)$request->input('crop-bank_account_document.width'),
                            (int)$request->input('crop-bank_account_document.height'),
                            (int)$request->input('crop-bank_account_document.x'),
                            (int)$request->input('crop-bank_account_document.y'))
                            ->resize(800, 450, function ($constraint) {
                                return $constraint->aspectRatio();
                            })
                            ->encode('jpeg')
                            ->save($path . '/' . $image_name)
                            ->destroy();
                    }

                    $newbank = $instance->bank()->create([
                        'bank' => $request->input('bank'),
                        'bank_account_name' => $request->input('bank_account_name'),
                        'bank_account_number' => $request->input('bank_account_number'),
                        'status' => 1,
                        'bank_account_document' => $image_name,
                    ]);
                    updateAchievement($instance);
                    $bankAtas = $image_name;
                }

            endif;
            if ($request->get('allow_bca')) {
                $image_name = null;
                $bankAtas = null;
                if ($request->get('name_rekening') || $request->get('no_rekening')) {
                    if ($transfer) {
                        if ($request->get('bank') == 'BCA' && empty($request->hasFile('upload_document'))) {
                            // $file = public_path('uploads/bank_manual/'.$transfer->upload_document);
                            // \File::delete($file);

                            $imageBank = $bankAtas ? $bankAtas : $bank->bank_account_document;
                            $path = public_path('uploads/bank_manual');
                            $source = public_path('uploads/bank_document/' . $imageBank);

                            // Buat folder jika tidak ada
                            \File::isDirectory($path) or \File::makeDirectory($path, 0777, true, true);

                            if (file_exists($source) && copy($source, public_path('uploads/bank_manual/' . $imageBank))) {
                                $image_name = $imageBank;
                            }
                        }

                        if ($request->hasFile('upload_document')) {
                            if ($transfer->upload_document) {
                                $file = public_path('uploads/bank_manual/' . $transfer->upload_document);
                                \File::delete($file);
                            }
                            $path = public_path('uploads/bank_manual');
                            $image_name = pathinfo($request->file('upload_document')->hashName(),
                                    PATHINFO_FILENAME) . '.jpg';
                            // Buat folder jika tidak ada
                            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                            $rotate = (int)$request->input('crop-upload_document.rotate');
                            $img = Image::make($request->file('upload_document'));
                            if ($rotate !== 0):
                                $img->rotate(360 - $rotate);
                            endif;

                            $img->crop((int)$request->input('crop-upload_document.width'),
                                (int)$request->input('crop-upload_document.height'),
                                (int)$request->input('crop-upload_document.x'),
                                (int)$request->input('crop-upload_document.y'))
                                ->resize(800, 450, function ($constraint) {
                                    return $constraint->aspectRatio();
                                })
                                ->encode('jpeg')
                                ->save($path . '/' . $image_name)
                                ->destroy();
                        }
                        $instance->transfer_manual->update([
                            'no_rekening' => $request->input('no_rekening'),
                            'name_rekening' => $request->input('name_rekening'),
                            'status' => ManualTransferStatus::StatusApprove,
                            'upload_document' => $image_name ? $image_name : $transfer->upload_document,
                            'code_payment' => $paymentlist->code_payment
                        ]);

                    } else {
                        if ($request->get('bank') == 'BCA' && empty($request->hasFile('upload_document'))) {
                            // $file = public_path('uploads/bank_manual/'.$transfer->upload_document);
                            // \File::delete($file);

                            $imageBank = $bankAtas ? $bankAtas : $bank->bank_account_document;
                            $path = public_path('uploads/bank_manual');
                            $source = public_path('uploads/bank_document/' . $imageBank);

                            // Buat folder jika tidak ada
                            \File::isDirectory($path) or \File::makeDirectory($path, 0777, true, true);

                            if (file_exists($source) && copy($source, public_path('uploads/bank_manual/' . $imageBank))) {
                                $image_name = $imageBank;
                            }
                        }

                        if ($request->hasFile('upload_document')) {
                            $path = public_path('uploads/bank_manual');
                            $image_name = pathinfo($request->file('upload_document')->hashName(),
                                    PATHINFO_FILENAME) . '.jpg';
                            // Buat folder jika tidak ada
                            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

                            $rotate = (int)$request->input('crop-upload_document.rotate');
                            $img = Image::make($request->file('upload_document'));
                            if ($rotate !== 0):
                                $img->rotate(360 - $rotate);
                            endif;

                            $img->crop((int)$request->input('crop-upload_document.width'),
                                (int)$request->input('crop-upload_document.height'),
                                (int)$request->input('crop-upload_document.x'),
                                (int)$request->input('crop-upload_document.y'))
                                ->resize(800, 450, function ($constraint) {
                                    return $constraint->aspectRatio();
                                })
                                ->encode('jpeg')
                                ->save($path . '/' . $image_name)
                                ->destroy();
                        }
                        $instance->transfer_manual()->create([
                            'no_rekening' => $request->input('no_rekening'),
                            'name_rekening' => $request->input('name_rekening'),
                            'status' => ManualTransferStatus::StatusApprove,
                            'upload_document' => $image_name,
                            'code_payment' => $paymentlist->code_payment
                        ]);
                    }
                }
            } else {
                if ($transfer) {
                    $instance->transfer_manual->update([
                        'status' => ManualTransferStatus::StatusInActive,
                    ]);
                }
            }

            $instance->save();
            \DB::commit();
            updateAchievement($instance);
//            if ($notifWidget){
//                Notify::onboard()->setContent('Membuat Perubahan google map di pengaturan '.Carbon::now()->toDateTimeString())->send();
//            }
            return json_encode([
                'status' => 200,
                'message' => \trans('setting_provider.company_updated') . " <a target='_blank' href='http://" . $instance->domain_memoria . "'>" . \trans('setting_provider.preview_now') . "</a>"
            ]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);
        }

    }

    /**
     * function get report data
     *
     * @param mixed $request
     *
     * @return void
     */
    public function get_report(Request $request)
    {
        $this->initalize($request);
        $company = Company::find(auth()->user()->id_company);
        $find = (object)['company' => \auth('web')->user()->company->id_company];
        $report_number_order = $this->get_number_of_order($find);
        $report_value_order = $this->get_value_of_order($find);
        $report_total_paid = $this->get_total_paid($find);
        $report_total_discount = $this->get_total_discount($find);
        $report_total_paid_internal = $this->get_total_paid_internal($find);
        $report_total_paid_online = $this->get_total_paid_online($find);
        $reimbursement = Order::whereIdCompany($company->id_company)->whereHas('voucher', function ($voucher) {
            $voucher->where('by_gomodo', 1);
        })->where('reimbursement', 0)->where('status', 1);
        $incoming_reimbursement_count = $reimbursement->count();
        $incoming_reimbursement_value = $reimbursement->sum('voucher_amount');
        $incoming_settlement = Order::whereIdCompany($company->id_company)->where('status', 1)->where('payment_list', '!=', 'Manual Transfer')
            ->whereHas('payment', function ($payment) {
                $payment->where('payment_gateway', '!=', 'Cash On Delivery')->where('status', 'PENDING');
            })->sum('total_amount');

        $journal = app('\App\Services\JournalService');
        $report_total_saldo = json_decode($journal->get_company_total_balance($this->company)->getContent());

        $report_total_balance = json_decode($journal->get_company_total_balance_all($this->company)->getContent());
        $totalViewedProduct = Product::where('id_company', $this->company)->sum('viewed');
        $newValue = Order::whereIdCompany($company->id_company)->where('status', 1)->where('payment_list', '!=', 'Manual Transfer')
            ->whereHas('payment', function ($payment) {
                $payment->where('payment_gateway', '!=', 'Cash On Delivery');
            })->sum('total_amount');
        $wit = WithdrawRequest::where(['id_company' => $this->company])->whereIn('status', [0, 1])->selectRaw('sum(amount) as total')->first()->total;
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'number_of_order' => number_format($report_number_order, 0),
                'value_of_order' => number_format($report_value_order, 0),
                'total_paid' => number_format($report_total_paid, 0),
                'total_discount' => number_format($report_total_discount, 0),
                'total_saldo' => number_format($report_total_saldo->data->total_balance, 0),
                'total_balance' => number_format($report_total_balance->data->total_balance, 0),
                'total_paid_internal' => number_format($report_total_paid_internal, 0),
                'total_paid_online' => number_format($report_total_paid_online, 0),
                'total_incoming_reimbursement' => number_format($incoming_reimbursement_value, 0),
                'total_incoming_settlement' => number_format($incoming_settlement, 0),
                'total_guest' => number_format($totalViewedProduct, 0),
                'saldo' => $newValue - $wit,
//                'total_incoming_settlement' => $incoming_settlement,

            ]
        ]);
    }

    /**
     * function get number of order
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_number_of_order($find)
    {
        $report = \App\Models\Order::where(['id_company' => $find->company])->where('status', 1)->count();
        return $report;
    }

    /**
     * function get value of order
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_value_of_order($find)
    {
        $report = \App\Models\Order::where(['id_company' => $find->company])->whereNotIn('status',
            [5, 6, 7])->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        return $report->total;
    }

    /**
     * function get total paid
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_total_paid($find)
    {
        $id_company = $find->company;
        $report = \App\Models\Order::where([
            'id_company' => $find->company,
            'status' => 1
        ])->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        $asuransi = (int)AdditionalOrder::whereHas('order', function ($o) use ($id_company) {
            $o->where('id_company', $id_company)->where('status', 1)->where('payment_list', '!=', 'Manual Transfer')
                ->whereHas('payment', function ($payment) {
                    $payment->where('payment_gateway', '!=', 'Cash On Delivery');
                });
        })->where('type', 'insurance')->sum('total');

        return $report->total - $asuransi;
    }

    /**
     * function get total discount
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_total_discount($find)
    {
        $report = \App\Models\Order::where([
            'id_company' => $find->company,
            'status' => 1
        ])->whereNotNull('id_voucher')->selectRaw(" SUM(total_amount) as total ")->first();
        return $report->total;
    }

    /**
     * function get total paid internal
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_total_paid_internal($find)
    {
        $report = \App\Models\Order::where(['id_company' => $find->company, 'status' => 1])->whereHas('payment', function ($payment) {
            $payment->whereIn('payment_gateway', ['Cash On Delivery', 'Manual Transfer BCA']);
        })->selectRaw(" SUM(total_amount) as total ")->first();
        return $report->total;
    }

    /**
     * function get total paid online
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_total_paid_online($find)
    {
        $id_company = $find->company;
        $report = \App\Models\Order::where(['id_company' => $find->company, 'status' => 1])->where('payment_list', '!=', 'Manual Transfer')->whereHas('payment',
            function ($payment) {
                $payment->where('payment_gateway', '!=', 'Cash On Delivery');
            })->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        $asuransi = (int)AdditionalOrder::whereHas('order', function ($o) use ($id_company) {
            $o->where('id_company', $id_company)->where('status', 1)->whereHas('payment',
                function ($payment) {
                    $payment->where('payment_gateway', '!=', 'Cash On Delivery');
                });
        })->where('type', 'insurance')->sum('total');

        return $report->total - $asuransi;
    }

    private function changeBank($request, $bank)
    {
        if ($request->hasFile('bank_account_document')) {
            $path = public_path('uploads/bank_document');
            $image_name = pathinfo($request->file('bank_account_document')->hashName(), PATHINFO_FILENAME) . '.jpg';
            // Buat folder jika tidak ada
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

            $rotate = (int)$request->input('crop-bank_account_document.rotate');
            $img = Image::make($request->file('bank_account_document'));
            if ($rotate !== 0):
                $img->rotate(360 - $rotate);
            endif;

            $img->crop((int)$request->input('crop-bank_account_document.width'),
                (int)$request->input('crop-bank_account_document.height'),
                (int)$request->input('crop-bank_account_document.x'),
                (int)$request->input('crop-bank_account_document.y'))
                ->resize(800, 450, function ($constraint) {
                    return $constraint->aspectRatio();
                })
                ->encode('jpeg')
                ->save($path . '/' . $image_name)
                ->destroy();

            \DB::transaction(function () use ($bank, $request, $image_name) {

                $requestbank = $bank->request_changes()->create([
                    'bank' => $request->get('bank'),
                    'bank_account_name' => $request->get('bank_account_name'),
                    'bank_account_number' => $request->get('bank_account_number'),
                    'bank_account_document' => $image_name,
                    'id_user' => auth('web')->id(),
                    'token' => generateRandomString(16),
                    'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                ]);
                $company = Company::find(auth()->user()->id_company);
                $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                if ($user->email) {
                    \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
                }

            });
            return ['status' => 'OK'];
        } else {
            \DB::transaction(function () use ($bank, $request) {

                $requestbank = $bank->request_changes()->create([
                    'bank' => $request->get('bank'),
                    'bank_account_name' => $request->get('bank_account_name'),
                    'bank_account_number' => $request->get('bank_account_number'),
                    'id_user' => auth('web')->id(),
                    'token' => generateRandomString(16),
                    'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                ]);
                $company = Company::find(auth()->user()->id_company);
                $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
            });
            return ['status' => 'OK'];
        }
    }


}
