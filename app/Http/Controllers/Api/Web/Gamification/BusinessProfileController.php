<?php

namespace App\Http\Controllers\Api\Web\Gamification;

use App\Http\Requests\BankFormRequest;
use App\Models\AchievementDetail;
use App\Models\Order;
use App\Models\UserAgent;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Image;
use Stevebauman\Location\Facades\Location;

class BusinessProfileController extends Controller
{
    use DiscordTrait;
    private $company;

    public function init()
    {
        if (auth()->check()) {
            $company = UserAgent::find(auth()->id())->company;
            $company->city;
            $company->bank;
            $company->categories;
            $this->company =$company;
        }else{
            return apiResponse(401,'unauthenticated');
        }

    }

    public function progress()
    {
        $this->init();
        $data['achievement_status'] = $this->company->achievements()->where('achievement_slug',
            'business-profile')->first()->pivot->company_achievement_status;
        $data['incomplete'] = $this->company->achievement_details()
            ->whereHas('achievement', function ($achievement) {
                $achievement->where('achievement_slug', 'business-profile');
            })->where('achievement_detail_company.achievement_status', 0)->count();

        $data['complete'] = $this->company->achievement_details()
            ->whereHas('achievement', function ($achievement) {
                $achievement->where('achievement_slug', 'business-profile');
            })->where('achievement_detail_company.achievement_status', 1)->count();
        return apiResponse(200, 'OK', $data);

    }

    public function current(Request $request)
    {

        $this->init();
        $data = $this->company->achievement_details()
            ->whereHas('achievement', function ($achievement) {
                $achievement->where('achievement_slug', 'business-profile');
            })
            ->where('achievement_detail_company.achievement_status', 0);
        if (!$check = $data->first()) {
            return apiResponse(200, 'No Pending Gamification');
        }

        if ($request->has('order_number')) {
            $data->where('order_number', $request->input('order_number'));

        }
        if (!$data->first()) {
            return apiResponse(404, 'not found');
        }
        $result['current'] = $data->first()->only(['slug', 'title_en', 'title_id', 'order_number']);
        $result['company_data'] = $this->company;
        $result['before'] = $this->company->achievement_details()
            ->whereHas('achievement', function ($achievement) {
                $achievement->where('achievement_slug', 'business-profile');
            })
            ->where('achievement_detail_company.achievement_status', 0)
            ->where('order_number', '<', (int)($result['current']['order_number']))
            ->first(['slug', 'title_en', 'title_id', 'order_number']);
        $result['next'] = $this->company->achievement_details()
            ->whereHas('achievement', function ($achievement) {
                $achievement->where('achievement_slug', 'business-profile');
            })
            ->where('achievement_detail_company.achievement_status', 0)
            ->where('order_number', '>', (int)($result['current']['order_number']))
            ->first(['slug', 'title_en', 'title_id', 'order_number']);
        $result['ownership_status'] = $this->company->ownership_status;
        return apiResponse(200, 'OK', $result);
    }

    public function updateBusinessType(Request $request)
    {
        $this->init();
        $rules = [
            'business_category' => 'required|array|min:1',
            'business_category.*' => 'required|exists:tbl_business_category,id',
            'ownership_status' => 'required|in:personal,corporate',
        ];
        $this->validate($request, $rules);
        if ($this->company->kyc && $request->input('ownership_status') != $this->company->ownership_status) {
            $this->company->kyc->delete();
        }
        $this->company->update([
            'ownership_status' => $request->input('ownership_status'),
        ]);
        $this->company->categories()->sync($request->input('business_category'));
        $achievementDetail = AchievementDetail::where('slug', 'business_type')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }

    public function updateAboutCompany(Request $request)
    {
        $this->init();
        $rules = [
            'short_description' => 'required|min:10|max:150',
//            'about' => 'required|max:300'
            'about' => 'required'
        ];

        $this->validate($request, $rules);
        $this->company->update([
            'short_description' => strip_tags($request->input('short_description')),
            'about' => clean($request->get('about'), 'simple')
        ]);
        $achievementDetail = AchievementDetail::where('slug', 'about_company')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }

    public function updateCompanyAddress(Request $request)
    {
        $this->init();
        $rules = [
            'id_city' => 'required|exists:tbl_city,id_city',
            'address_company' => 'required|min:6',
            'postal_code' => 'required|digits:5',
            'lat' => 'required',
            'long' => 'required',
            'google_place_id' => 'required'
        ];

        $this->validate($request, $rules);
        $dataUpdate = [
            'id_city' => $request->input('id_city'),
            'address_company' => $request->input('address_company'),
            'postal_code' => $request->input('postal_code')
        ];
        if ((float)$request->input('lat') !== 0 || (float)$request->input('long') !== 0) {
            $dataUpdate['lat'] = (float)$request->input('lat');
            $dataUpdate['long'] = (float)$request->input('long');
        }
        $dataUpdate['google_place_id'] = $request->input('google_place_id');

        $this->company->update($dataUpdate);
        $achievementDetail = AchievementDetail::where('slug', 'address_company')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }


    public function updateContact(Request $request)
    {
        $this->init();
        if ($this->company->agent->email) {
            $rules = [
                'email_company' => 'required|email|max:100',
                'phone_company' => 'required|phone:AUTO,ID',
                'facebook_company' => 'nullable',
                'instagram_company' => 'nullable'
            ];
        } else {
            $rules = [
                'email_company' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('tbl_user_agent', 'email')->whereNot('id_user_agen', $this->company->agent->id_user_agen)
                ],
//                'email_company' => 'required|email|max:100|unique:tbl_user_agent,email,'.$this->company->agent->id_user_agen,
                'phone_company' => 'required|phone:AUTO,ID',
                'facebook_company' => 'nullable',
                'instagram_company' => 'nullable'
            ];
        }


        $this->validate($request, $rules);
        $this->company->update([
            'email_company' => $request->input('email_company'),
            'phone_company' => $request->input('phone_company'),
            'facebook_company' => $request->input('facebook_company'),
            'twitter_company' => $request->input('instagram_company')
        ]);
        if ($this->company->agent->email == null) {
            $this->company->agent->update(['email' => $request->input('email_company')]);
        }

        $achievementDetail = AchievementDetail::where('slug', 'contact_us')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }

    public function updateLogo(Request $request)
    {
        $this->init();
        if (empty($this->company->logo)):
            $rule = [
                'logo' => 'required_without:default_logo|file|mimes:png,jpg,jpeg',
                'default_logo' => 'required_without:logo',
            ];
            $this->validate($request, $rule);
        endif;
        if ($request->hasFile('logo')) {
            $image_name = 'logo-' . Str::slug($this->company->company_name) . '-' . time() . '.' . $request->file('logo')->getClientOriginalExtension();

            $logo_sizes = [
                [
                    'width' => 50,
                    'height' => 50,
                    'path' => public_path('uploads/company_logo')
                ],
                [
                    'width' => 32,
                    'height' => 32,
                    'path' => public_path('uploads/company_logo/favicon')
                ]
            ];
            $old = $this->company->logo;
            foreach ($logo_sizes as $size) {
                File::isDirectory($size['path']) or File::makeDirectory($size['path'], 0777, true, true);
                $img = Image::make($request->file('logo'));
                $rotate = (int)$request->input('crop-logo.rotate');
                if ($rotate !== 0):
                    $img->rotate(360 - $rotate);
                endif;

                if ($img->crop((int)$request->input('crop-logo.width'), (int)$request->input('crop-logo.height'),
                        (int)$request->input('crop-logo.x'),
                        (int)$request->input('crop-logo.y'))
                    ->resize($size['width'], $size['height'], function ($constraint) {
                        return $constraint->aspectRatio();
                    })
                    ->encode('png')
                    ->save($size['path'] . '/' . $image_name)) {
                    $img->destroy();
                    Storage::disk('uploads')->delete($size['path'] . '/' . $old);
                    $this->company->logo = $image_name;

                };
            }

            $this->company->logo = $image_name;

        } elseif ($request->get('default_logo')) {
            $this->company->logo = $request->get('default_logo');
        }
        $this->company->save();
        $achievementDetail = AchievementDetail::where('slug', 'company_logo')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }

    public function updateBanner(Request $request)
    {
        $this->init();
        if (empty($this->company->banner)):
            $rule = [
                'banner' => 'required_without:default_banner|file|mimes:png,jpg,jpeg',
                'default_banner' => 'required_without:banner',
            ];
            $this->validate($request, $rule);
        endif;
        if ($request->hasFile('banner')) {
            $path = public_path('uploads/banners');
            $image_name = 'banner-' . $this->company->company_name . '-' . time() . '.' . $request->file('banner')->getClientOriginalExtension();
            // Buat folder jika tidak ada
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            $old = $this->company->banner;
            $rotate = (int)$request->input('crop-banner.rotate');
            $img = Image::make($request->file('banner'));
            if ($rotate !== 0):
                $img->rotate(360 - $rotate);
            endif;

            if ($img->crop((int)$request->input('crop-banner.width'), (int)$request->input('crop-banner.height'),
                    (int)$request->input('crop-banner.x'), (int)$request->input('crop-banner.y'))
                ->resize(1600, 900, function ($constraint) {
                    return $constraint->aspectRatio();
                })
                ->encode('jpeg')
                ->save($path . '/' . $image_name)) {
                $img->destroy();
                Storage::disk('uploads')->delete($path . '/' . $old);
                $this->company->banner = $image_name;
            }


        } elseif ($request->get('default_banner')) {
            $this->company->banner = $request->get('default_banner');
        }
        $this->company->save();
        $achievementDetail = AchievementDetail::where('slug', 'company_banner')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
    }

    public function updateSEO(Request $request)
    {
        $this->init();
        $rules = [
            'title' => 'required|min:3',
            'description' => 'required|min:10|max:150',
            'keywords' => 'required|array|min:1',
            'keywords.*' => 'required'
        ];

        $this->validate($request, $rules);

        $this->company->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'keywords' => implode(', ', $request->input('keywords'))
        ]);
        $achievementDetail = AchievementDetail::where('slug', 'seo')->first();
        $this->company->achievement_details()->updateExistingPivot($achievementDetail->id, ['achievement_status' => 1]);
        return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));


    }

    public function updateBank(Request $request)
    {
        $this->init();
        $bank = $this->company->bank;
        if ($bank) {
            $achievementDetail = AchievementDetail::where('slug', 'bank_account')->first();
            if ($check = $this->company->achievement_details()->where('id', $achievementDetail->id)->first()) {
                if ($check->pivot->achievement_status == '1') {
                    return apiResponse(400, 'You have already complete this task');
                }
            }
            $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                ['achievement_status' => 1]);
            return apiResponse(200, 'OK');
        }
        $rule = [
            'bank' => 'required',
            'bank_account_name' => 'required|max:50',
            'bank_account_number' => 'required|min:5|max:25',
            'bank_account_document' => 'sometimes|image|mimes:png,jpg,jpeg'
        ];

        $attributes = [
            'bank' => strtolower(trans('bank_provider.select_bank')),
            'bank_account_name' => strtolower(trans('bank_provider.account_name')),
            'bank_account_number' => strtolower(trans('bank_provider.account_number')),
            'bank_account_document' => strtolower(trans('bank_provider.bank_document'))
        ];

        $this->validate($request, $rule, [], $attributes);
        $image_name = null;
        if ($request->hasFile('bank_account_document')) {
            $path = public_path('uploads/bank_document');
            $image_name = Str::slug('Bank Account Document ' . $this->company->company_name) . '.' . $request->file('bank_account_document')->getClientOriginalExtension();
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            if ($img = Image::make($request->file('bank_account_document'))
                ->save($path . '/' . $image_name)) {
                $img->destroy();
                $newbank = $this->company->bank()->create([
                    'bank' => $request->input('bank'),
                    'bank_account_name' => $request->input('bank_account_name'),
                    'bank_account_number' => $request->input('bank_account_number'),
                    'status' => 1,
                    'bank_account_document' => $image_name,
                ]);

                $achievementDetail = AchievementDetail::where('slug', 'bank_account')->first();
                $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                    ['achievement_status' => 1]);
                return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
            }

        } else {
            $newbank = $this->company->bank()->create([
                'bank' => $request->input('bank'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_number' => $request->input('bank_account_number'),
                'status' => 1,
                'bank_account_document' => $image_name,
            ]);

            $achievementDetail = AchievementDetail::where('slug', 'bank_account')->first();
            $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                ['achievement_status' => 1]);
            return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
        }

        return apiResponse(400, __('general.whoops'));
    }

    public function updateKYC(Request $request)
    {
        $this->init();
        if ($this->company->kyc) {
            $achievementDetail = AchievementDetail::where('slug', 'kyc')->first();
            if ($check = $this->company->achievement_details()->where('id', $achievementDetail->id)->first()) {
                if ($check->pivot->achievement_status == '1') {
                    return apiResponse(400, 'You have already complete this task');
                }
            }
            $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                ['achievement_status' => 1]);
            return apiResponse(200, 'OK');
        }
        if ($this->company->ownership_status === 'corporate') {
            $rule = [
                'company_tax_number' => 'required|image',
                'company_establishment_deed' => 'required|image',
                'company_register_certification' => 'required|image',
                'company_domicile' => 'required|image',
                'company_business_license' => 'required|image',
                'owner_identity_card' => 'required|image',
                'owner_tax_number' => 'required|image',
            ];
            $this->validate($request, $rule);
            try {
                \DB::beginTransaction();
                $path = 'uploads/kyc/' . $this->company->id_company;
                if (!File::isDirectory(Storage::disk('public')->path($path))) {
                    File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                }
                $data = [];
                foreach ($request->files as $keyname => $file) {
                    $source = $file;
                    $name = 'kyc-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                    if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                        $data[$keyname] = Storage::url($path . '/' . $name);
                    }
                }
                $this->company->kyc()->create($data);
                \DB::commit();
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $loc = Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New KYC Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
                $content .= '```';
                $content .= "Company Name  : " . $this->company->company_name . "\n";
                $content .= "Domain Gomodo : " . $http . $this->company->domain_memoria . "\n";
                $content .= "Email Company : " . $this->company->email_company . "\n";
                if ($this->company->agent->email):
                    $content .= "Login Email   : " . $this->company->agent->email . "\n";
                endif;
                $content .= "Phone Number  : " . $this->company->agent->phone . "\n";
                $content .= "IP Address    : " . $ip . "\n";
                $content .= "City name     : " . $loc->cityName . "\n";
                $content .= "Region Name   : " . $loc->regionName . "\n";
                $content .= "Country Code  : " . $loc->countryCode . "\n";
                $content .= '```';


                $this->sendDiscordNotification(sprintf('%s', $content), 'kyc');
                $achievementDetail = AchievementDetail::where('slug', 'kyc')->first();
                $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                    ['achievement_status' => 1]);
                return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, '', getException($exception));
            }
        } else {
            $rule = [
                'identity_card' => 'required|image',
                'family_card' => 'required|image',
                'tax_number' => 'required|image',
                'police_certificate' => 'sometimes|image',
                'bank_statement' => 'sometimes|image',
                'photo' => 'sometimes|image',
                'phone_number' => 'required|numeric|digits_between:6,20',
                'address' => 'required',
            ];
            $this->validate($request, $rule);
            try {
                \DB::beginTransaction();
                $path = 'uploads/kyc/' . $this->company->id_company;
                if (!File::isDirectory(Storage::disk('public')->path($path))) {
                    File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                }
                $data = [];
                foreach ($request->files as $keyname => $file) {
                    $source = $file;
                    $name = 'kyc-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                    if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                        $data[$keyname] = Storage::url($path . '/' . $name);
                    }
                }
                $data['phone_number'] = $request->input('phone_number');
                $data['address'] = $request->input('address');
                $this->company->kyc()->create($data);
                \DB::commit();
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $loc = Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New KYC Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
                $content .= '```';
                $content .= "Company Name  : " . $this->company->company_name . "\n";
                $content .= "Domain Gomodo : " . $http . $this->company->domain_memoria . "\n";
                $content .= "Email Company : " . $this->company->email_company . "\n";
                if ($this->company->agent->email):
                    $content .= "Login Email   : " . $this->company->agent->email . "\n";
                endif;
                $content .= "Phone Number  : " . $this->company->agent->phone . "\n";
                $content .= "IP Address    : " . $ip . "\n";
                $content .= "City name     : " . $loc->cityName . "\n";
                $content .= "Region Name   : " . $loc->regionName . "\n";
                $content .= "Country Code  : " . $loc->countryCode . "\n";
                $content .= '```';


                $this->sendDiscordNotification(sprintf('%s', $content), 'kyc');
                $achievementDetail = AchievementDetail::where('slug', 'kyc')->first();
                $this->company->achievement_details()->updateExistingPivot($achievementDetail->id,
                    ['achievement_status' => 1]);
                return apiResponse(200, 'OK', $this->responseGamification($achievementDetail->order_number));
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, '', getException($exception));
            }
        }
    }

    private function responseGamification($number = null)
    {
        $this->init();
        if ($check = $this->company->achievements()->where('achievement_slug', 'business-profile')->first()) {
            if ($check->pivot->company_achievement_status == '1') {
                return [
                    'complete' => true,
                    'before' => null,
                    'next' => null,
                ];
            }
        }

        if ($this->company->achievement_details()->whereHas('achievement', function ($a) {
            $a->where('achievement_slug', 'business-profile');
        })->wherePivot('achievement_status', 0)->first()) {
            if ($number) {
                $next = $this->company->achievement_details()
                    ->whereHas('achievement', function ($achievement) {
                        $achievement->where('achievement_slug', 'business-profile');
                    })
                    ->where('achievement_detail_company.achievement_status', 0)
                    ->where('order_number', '>', (int)$number)
                    ->first();
                $before = $this->company->achievement_details()
                    ->whereHas('achievement', function ($achievement) {
                        $achievement->where('achievement_slug', 'business-profile');
                    })
                    ->where('achievement_detail_company.achievement_status', 0)
                    ->where('order_number', '>', (int)$number)
                    ->first();

                return [
                    'complete' => false,
                    'before' => $before,
                    'next' => $next,
                ];
            }

            $next = $this->company->achievement_details()
                ->whereHas('achievement', function ($achievement) {
                    $achievement->where('achievement_slug', 'business-profile');
                })
                ->where('achievement_detail_company.achievement_status', 0)
                ->orderBy('order_number')
                ->first();
            return [
                'complete' => false,
                'before' => null,
                'next' => $next,
            ];
        }
        $this->company->achievements()->where('achievement_slug', 'business-profile')->update(['company_achievement_status' => 1]);
        return [
            'complete' => true,
            'before' => null,
            'next' => null,
        ];
    }

    public function getNext(Request $request)
    {
        $detail  = AchievementDetail::whereSlug($request->get('slug'))->first();
        if ($detail){
            $orderNumber = $detail->order_number;
            return apiResponse(200,'OK',$this->responseGamification($orderNumber));
        }

        return apiResponse(404,__('general.not_found'));
    }
}
