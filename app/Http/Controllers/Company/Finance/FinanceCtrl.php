<?php

namespace App\Http\Controllers\Company\Finance;

use App\Enums\FinanceOption;
use App\Http\Requests\FinanceRequest;
use App\Jobs\SendEmail;
use App\Models\CategoryFinance;
use App\Models\Company;
use App\Models\Finance;
use App\Models\Kyc;
use App\Models\TimeFinance;
use App\Models\TypeFinance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use mysql_xdevapi\Exception;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use File;
use Image;
use Storage;

class FinanceCtrl extends Controller
{
    use DiscordTrait;

    public function index()
    {
        $company = auth('web')->user()->company;
        $category = CategoryFinance::where('name_finance', 'koinworks')->first();
        $type_finance = TypeFinance::where('category_finance_id', $category->id)->get();
        $time_finance = TimeFinance::where('category_finance_id', $category->id)->get();
        return view('dashboard.company.finance.index', compact('company', 'type_finance', 'category', 'time_finance'));
    }

    public function checkKyc(Request $request)
    {
        $company = auth('web')->user()->company;

        $cek = Kyc::where('id_company', $company->id_company)->first();
        if ($cek->status == 'approved') {
            return response()->json([
                'status' => 'oke',
                'data' => $cek
            ]);
        } elseif ($cek->status == 'need_approval') {
            return response()->json([
                'status' => false,
                'message' => 'Please input data KYC'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Request your KYC rejected'
            ]);
        }
    }

    public function store(FinanceRequest $request)
    {
        if ($request->get('amount') < 10000000 || $request->get('amount') > 2000000000) {
            apiResponse('403', 'Amount nya kebanyakan atau kekurangan');
        }

        try {
            \DB::beginTransaction();
            $company = auth('web')->user()->company;

            if ($company->kyc->status == 'approved'){
                $time = TimeFinance::find($request->time_finance);
                $type = TypeFinance::find($request->type_finance);
                $kyc = Kyc::where('id_company', $company->id_company)->first();

                $min_suku_bunga = 1.5 * $time->duration_time;
                $max_suku_bunga = 2 * $time->duration_time;

                $data['time_finance_id'] = $time->id;
                $data['type_finance_id'] = $type->id;
                $data['amount'] = $request->amount;
                $data['min_suku_bunga'] = $min_suku_bunga;
                $data['max_suku_bunga'] = $max_suku_bunga;
                $data['fee_provisi'] = 0;
                $data['fee_penalty_delay'] = 0;
                $data['fee_settled'] = 0;
                $data['fee_admin'] = 0;
                $data['fee_insurance'] = 0;
                $data['status'] = FinanceOption::status0;

                $finance = $company->finance()->create($data);
                $path = 'uploads/finance';
                if (!File::isDirectory(Storage::disk('public')->path($path))) {
                    File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                }
                $image = [];
                foreach ($request->files as $keyname => $file) {
                    $source = $file;
                    $name = 'finance - [' . now().'] - '. generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                    if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                        $image[$keyname] = Storage::url($path . '/' . $name);
                    }
                }
                $verification = $finance->verification()->create($image);

                $img = [];
                $dataKyc = [];
                if ($request->get('use_kyc') == '1'){
                    if ($company->ownership_status == 'personal'){
                        $img = [
                            'ktp' => $kyc->identity_card,
                            'npwp' => $kyc->tax_number,
                            'family_card' => $kyc->family_card
                        ];
                    } else {
                        $img = [
                            'ktp' => $kyc->owner_identity_card,
                            'npwp' => $kyc->company_tax_number,
                            'siup' => $kyc->company_business_license,
                            'founding_deed' => $kyc->company_establishment_deed,
                            'company_signatures' => $kyc->company_register_certification,
                        ];
                    }

                    foreach ($img as $key => $item) {
                        if (empty($item)) continue;
                        $path = 'uploads/finance';
                        $source = public_path($item);
                        $getClient = pathinfo(storage_path('app/public' . str_replace('storage/', '', $item)), PATHINFO_EXTENSION);
                        if (!File::isDirectory(Storage::disk('public')->path($path))) {
                            File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                        }
                        $name = 'finance - [' . now().'] - '. generateRandomString(6) . time() . '.' . $getClient;
//                        if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))){
//                            $dataKyc[$key] = Storage::url($path . '/' . $name);
//                        }

                        if (file_exists($source) && copy($source, storage_path('app/public/uploads/finance/'.$name))){
                            $dataKyc[$key] = Storage::url($path . '/' . $name);
                        }
                    }
                    $finance->verification->update($dataKyc);
                }
                $dataImage = array_merge($image,$dataKyc);
                $this->mailBisdev($company, $finance->id, $dataImage);
                $this->mailProvider($company, $finance->id);
                \DB::commit();
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $company;
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New Finance '.$type->categoryType->name_finance.' '. Carbon::now()->format('d M Y H:i:s').'**';
                $content .= '```';
                $content .= "Company Name    : " . $newCompany->company_name . "\n";
                $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
                $content .= "Email Company   : " . $newCompany->email_company . "\n";
                $content .= "Category Finance : " . $type->categoryType->name_finance . "\n";
                $content .= "IP Address      : " . $ip . "\n";
                $content .= "City name       : " . $loc->cityName . "\n";
                $content .= "Region Name     : " . $loc->regionName . "\n";
                $content .= "Country Code    : " . $loc->countryCode . "\n";
                $content .= '```';

                $this->sendDiscordNotification($content,'finance');

                return apiResponse(200, 'Sukses Silahkan cek email anda');
            }

            return apiResponse(500, 'Silahkan isikan data KYC terlebih dahulu');
        } catch (\Exception $e) {
            \DB::rollback();
            return apiResponse(500, '', getException($e));
        }
    }

    public function mailBisdev($company, $id, $image)
    {
        $finance = Finance::where('id', $id)->first();
        $to = 'maryawisesa@gmail.com';
        if (env('APP_ENV', 'production') == 'local') {
            $to = 'hasutamori@gmail.com';
        }
        if (empty($to)) {
            return true;
        }

        $data = ['company' => $company, 'finance' => $finance];
        $subject = 'Info Tentang '.$finance->typeFinance->categoryType->name_finance;
        
        $template = view('mail.finance.mail-to-bisdev', $data)->render();
        $pdf = null;
        $image = null;
        dispatch(new SendEmail($subject, $to, $template, $image, $pdf, $data));
        
        // $from = 'support@gomodo.id';
        // $template = 'mail.finance.mail-to-bisdev';
        // \Mail::send($template, $data, function ($message) use ($to, $from, $subject, $image) {
        //     $message->to($to)->subject($subject);
        //     if (!empty($image)){
        //         foreach ($image as $item) {
        //             $message->attach(storage_path('app/public' . str_replace('storage/', '', $item)));
        //         }
        //     }
        //     $message->from($from, 'Admin Gomodo');
        // });

    }

    public function mailProvider($company, $id)
    {
        $finance = Finance::where('id', $id)->first();
        $subject = 'Info Finance';
        $to = $company->email_company;
        if (empty($to)){
            return false;
        }
        $data = [
            'company' => $company
        ];
        $template = view('mail.finance.mail-to-provider', $data)->render();
        $pdf = null;
        $image = null;
        dispatch(new SendEmail($subject, $to, $template, $image, $pdf, $data));
    }
}
