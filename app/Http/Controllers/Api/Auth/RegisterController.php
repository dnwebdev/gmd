<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\OtpRegister;
use App\Http\Requests\Api\Auth\Register;
use App\Http\Requests\Api\Auth\RegisterStepOne;
use App\Models\Company;
use App\Models\RestrictSubDomain;
use App\Models\Update;
use App\Models\UserAgent;
use App\Models\UserOtp;
use App\Notifications\Register\CompanyRegister;
use App\Traits\DiscordTrait;
use App\Traits\ZenzivaTrait;
use Carbon\Carbon;
use Gomodo\Discord\Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use ZenzivaTrait,DiscordTrait;
    public function stepOne(RegisterStepOne $request)
    {
        return apiResponse(200, 'OK');
    }

    public function register(Register $request)
    {
        $subdomain = preg_replace('/\s+/', '', strtolower($request->domain));
        if (RestrictSubDomain::whereSubdomain($subdomain)->first()) {
            return apiResponse(422, '', null,
                [
                    'domain' => [
                        trans('validation.another',
                            [
                                'attribute' => 'domain'
                            ]
                        )
                    ]
                ]
            );
        }
        try {
            \DB::beginTransaction();

            $newCompany = Company::create([
                'domain_memoria' => preg_replace('/\s+/', '', strtolower($request->input('domain'))) . '.' . env('APP_URL'),
                'company_name' => strip_tags($request->input('company_name')),
                'email_company' => $request->input('email'),
                'ownership_status' => $request->input('ownership_status'),
                'logo' => 'dest-operator/img/logo1.png',
                'banner' => 'dest-operator/img/banner1.jpg',
                'phone_company' => $request->input('phone'),
                'status' => 1
            ]);

            $newUser = UserAgent::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'id_company' => $newCompany->id_company,
                'language' => app()->getLocale(),
                'status' => 0,
                'phone' => $request->input('phone'),
            ]);

            $newCompany->categories()->sync($request->input('business_category'));

            $theme = \App\Models\Theme::first();

            if ($theme) {
                \App\Models\CompanyTheme::create([
                    'header_bgcolor' => $theme->header_bgcolor,
                    'navbar_bgcolor' => $theme->navbar_bgcolor,
                    'navbar_textcolor' => $theme->navbar_textcolor,
                    'body_bgcolor' => $theme->body_bgcolor,
                    'body_secondary_bgcolor' => $theme->body_secondary_bgcolor,
                    'button_primary_bgcolor' => $theme->button_primary_bgcolor,
                    'button_primary_textcolor' => $theme->button_primary_textcolor,
                    'button_secondary_bgcolor' => $theme->button_secondary_bgcolor,
                    'button_secondary_textcolor' => $theme->button_secondary_textcolor,
                    'status' => 1,
                    'id_company' => $newCompany->id_company,
                    'id_theme' => $theme->id_theme,
                ]);
            }

            $updates = Update::orderBy('created_at')->get();

            $newCompany->updates()->sync($updates);

            $company_token = \App\Models\CompanyToken::create([
                'token' => generateRandomString(32),
                'status' => 1,
                'id_company' => $newCompany->id_company,
                'expired_at' => Carbon::now()->addDay(7)
            ]);

            \DB::commit();

            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Provider ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name  : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Email Company : " . $newCompany->email_company . "\n";
            $content .= "Login Email   : " . $newCompany->agent->email . "\n";
            $content .= "Phone Number  : " . $newCompany->phone_company . "\n";
            $content .= "IP Address    : " . $ip . "\n";
            $content .= "City name     : " . $loc->cityName . "\n";
            $content .= "Region Name   : " . $loc->regionName . "\n";
            $content .= "Country Code  : " . $loc->countryCode . "\n";
            $content .= '```';
            $newUser->notifyNow(new CompanyRegister($company_token->token));

//            Notify::onboard()->setContent(sprintf('%s', $content))->send();
            $this->sendDiscordNotification(sprintf('%s', $content), 'onboarding');

            Mail::to('anindya.dewi@gomodo.tech')->sendNow(new \App\Mail\Register\NotifToGomodo($newCompany));
            return response()->json(['message' => 'Registration success please check your email', 'redirect' => route('login')], 200);
        } catch (\Exception $exception) {
            \DB::rollBack();
            dd($exception);
        }
    }

    public function otpTesting(Request $request)
    {
        $random = generateRandomString(4,'number');
        $randomLink = generateRandomString(10);
        $message = 'K0DE: '.$random.'. Berlaku 10 menit. Jangan berikan SMS ini ke siapapun ';
        $number = $request->get('phone');
        $this->sendOTP($number,$random);
    }

    public function OtpRegister(OtpRegister $request)
    {
        $subdomain = preg_replace('/\s+/', '', strtolower($request->domain));
        if (RestrictSubDomain::whereSubdomain($subdomain)->first() || in_array($subdomain,['pesona','bupsha','klhk'])) {
            return apiResponse(422, '', null,
                [
                    'domain' => [
                        trans('validation.another',
                            [
                                'attribute' => 'domain'
                            ]
                        )
                    ]
                ]
            );
        }
        \DB::beginTransaction();
        try {
            $newCompany = Company::create([
                'domain_memoria' => preg_replace('/\s+/', '', strtolower($request->input('domain'))) . '.' . env('APP_URL'),
                'company_name' => strip_tags($request->input('name')),
                'email_company' => $request->input('email'),
                'ownership_status' => checkRequestExists($request, 'ownership_status') ? $request->input('ownership_status') : 'personal',
                'logo' => 'dest-operator/img/logo1.png',
                'banner' => 'dest-operator/img/banner1.jpg',
                'phone_company' => $request->input('phone'),
                'status' => 1
            ]);
            $newUser = UserAgent::create([
                'first_name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->get('use') == 0 ? bcrypt($request->input('password')) : bcrypt(str_random(8)),
                'id_company' => $newCompany->id_company,
                'language' => app()->getLocale(),
                'status' => 0,
                'phone' => $request->input('phone'),
            ]);
            $updates = Update::orderBy('created_at')->get();

            $newCompany->updates()->sync($updates);

            if ($request->get('use') == 1){
                $random = generateRandomString(4,'number');
                $randomLink = generateRandomString(10);
                while (UserOtp::where('otp', $random)->where('type', 'register')->first()) {
                    $random = generateRandomString(4,'number');
                }
                $otp = UserOtp::create([
                    'user_id' => $newUser->id_user_agen,
                    'otp' => $random,
                    'shortcode' => $randomLink
                ]);

                \DB::commit();
                $message = 'K0DE: '.$random.'. Berlaku 10 menit. Jangan berikan SMS ini ke siapapun ';
                $number = $request->input('phone');
                $this->sendOTP($number,$random);
            } else{
                $company_token = \App\Models\CompanyToken::create([
                    'token' => generateRandomString(32),
                    'status' => 1,
                    'id_company' => $newCompany->id_company,
                    'expired_at' => Carbon::now()->addDay(7)
                ]);
                \DB::commit();
            }


            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Provider ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name  : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Email Company : " . $newCompany->email_company . "\n";
            if (isset($newUser->email)):
                $content .= "Login Email   : " . $newUser->email . "\n";
            endif;
            if (isset($newUser->phone)):
                $content .= "Phone Number  : " . $newUser->phone . "\n";
            endif;
            $content .= "IP Address    : " . $ip . "\n";
            $content .= "City name     : " . $loc->cityName . "\n";
            $content .= "Region Name   : " . $loc->regionName . "\n";
            $content .= "Country Code  : " . $loc->countryCode . "\n";
            $content .= '```';
            if ($request->get('use') == 1){
    //            Notify::onboard()->setContent(sprintf('%s', $content))->send();
                $this->sendDiscordNotification(sprintf('%s', $content), 'onboarding');
                // change next time
    //            Mail::to('anindya.dewi@gomodo.tech')->sendNow(new \App\Mail\Register\NotifToGomodo($newCompany));

                return apiResponse(200,__('general.success'),[
                    'message' => \trans('landing.register.success'),
                    'redirect'=>url('/auth/register/otp?phone='.$newUser->phone),
                    'submit_url'=>url('/auth/register/otp'),
                    'id'=>$otp->id,
                ]);
            } else{
                $newUser->notifyNow(new CompanyRegister($company_token->token));

                $this->sendDiscordNotification(sprintf('%s', $content), 'onboarding');
                Mail::to('anindya.dewi@gomodo.tech')->sendNow(new \App\Mail\Register\NotifToGomodo($newCompany));
//                return response()->json(['message' => 'Registration success please check your email', 'redirect' => route('login')], 200);
                return apiResponse(200,__('general.success'),[
                    'message' => \trans('landing.register.activation_email'),
                    'redirect'=> url('agent/login'),
                ]);
            }

        } catch (\Exception $exception) {
            return apiResponse(500, 'KO', getException($exception));
        }
    }

    public function resendOTP(Request $request)
    {
        /*Nunggu SMS Gateway*/

        $email = $request->input('phone');
        $user = UserAgent::where(function ($query) use ($email) {
            $query->where('phone', $email);
        })->first();
        if (!$user){
            return apiResponse(404, __('general.whoops'),null,['phone'=>[__('validation.exists',['attribute'=>'User'])]]);
        }
        if ($user->status == '1'){
            return apiResponse(422,__('general.whoops'),null,['phone'=>[__('auth.already_active')]]);
        }
        $random = generateRandomString(4,'number');
        $randomLink = generateRandomString(10);
        while (UserOtp::where('otp', $random)->where('type', 'register')->first()) {
            $random = generateRandomString(4,'number');
        }
        $otp = UserOtp::create([
            'user_id' => $user->id_user_agen,
            'otp' => $random,
            'shortcode' => $randomLink
        ]);
        $message = 'K0DE: '.$random.'. Berlaku 10 menit. Jangan berikan SMS ini ke siapapun ';
        $number = $request->input('phone');
        $this->sendOTP($number,$random);

        return apiResponse(200,__('general.success'),[
            'redirect'=>url('/auth/register/otp'),
            'submit_url'=>url('/auth/register/otp'),
            'id'=>$otp->id,
        ]);
    }

    public function kirim()
    {
//        $order = \App\Models\Order::whereStatus(1)->first();
//        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : \request()->ip();
//        $loc = \Stevebauman\Location\Facades\Location::get($ip);
//        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
//        if ($order->booking_type == 'online') {
//            $content = '**New PAID & SETTLED Online Booking '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
//        } else {
//            $content = '**New PAID & SETTLED E-Invoice '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
//        }
//        $newCompany = $order->company;
//
//        $content .= '```';
//        $content .= "Company Name    : ".$newCompany->company_name."\n";
//        $content .= "Domain Gomodo   : ".$http.$newCompany->domain_memoria."\n";
//        $content .= "Email Company   : ".$newCompany->email_company."\n";
//        $content .= "Phone Number    : ".$newCompany->phone_company."\n";
//        $content .= "Invoice Name    : ".$order->order_detail->product_name."\n";
//        $content .= "Payer Name      : ".$order->customer_info->first_name."\n";
//        $content .= "Payer Email     : ".$order->customer_info->email."\n";
//        $content .= "Total Nominal   : ".format_priceID($order->total_amount)."\n";
//        $content .= "Payment Method  : ".$order->payment->payment_gateway."\n";
//        if ($order->voucher):
//            $content .= "Use Voucher     :  Yes\n";
//            $content .= "Voucher Code    : ".$order->voucher_code."\n";
//            $content .= "Voucher Amount  : ".format_priceID($order->voucher_amount)."\n";
//            if ($order->voucher->by_gomodo == '1'):
//                $content .= "Voucher By      :  Gomodo\n";
//            else:
//                $content .= "Voucher By      :  Provider\n";
//            endif;
//        endif;
//        $content .= "IP Address      : ".$ip."\n";
//        $content .= "City name       : ".$loc->cityName."\n";
//        $content .= "Region Name     : ".$loc->regionName."\n";
//        $content .= "Country Code    : ".$loc->countryCode."\n";
//        $content .= '```';
//        $this->sendDiscordNotification(sprintf('%s', $content), 'transaction');
//
//        $message = 'Klik link ini untuk mengaktifkan status di Gomodo '.route('api:otp-register',
//                ['shortcode' => 'JGBJHS']).' K0DE: 865273. Berlaku 10 menit. Jangan berikan SMS ini ke siapapun ';
        $numbers = [
            '085712299001',
            '082241214466'
        ];
        $random = generateRandomString(4,'number');
        foreach ($numbers as $number):
            $this->sendOTP($number,$random);
        endforeach;

//        $message = 'Klik link ini untuk mengaktifkan status di gomodo'.route('api:otp-forgot-password',['shortcode'=>'JGBJHS']).' K0DE: 865273. Berlaku 10 menit. Jangan berikan SMS ini ke siapapun ';
//        $number = '082241214466';
//
////        dd($this->sendSMS($number,$message));
//        dd($this->sendOTP($number,7721));
    }
}
