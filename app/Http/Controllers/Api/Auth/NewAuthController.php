<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Company;
use App\Models\RestrictSubDomain;
use App\Models\Update;
use App\Models\UserAgent;
use App\Models\UserOtp;
use App\Services\TigService;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ZenzivaTrait;
use Illuminate\Validation\Rule;
use Kayiz\Woowa;

class NewAuthController extends Controller
{
    use DiscordTrait, ZenzivaTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, TigService $tigService)
    {
        $gomodoDomains = [
            'bupsha',
            'pesona',
            'klhk',
            'support',
            'gap'
        ];
        $restricts = [];

        foreach ($gomodoDomains as $gomodoDomain):
            $restricts[] = $gomodoDomain . '.' . env('APP_URL');
        endforeach;

        $restricts = array_merge($restricts, RestrictSubDomain::get(['subdomain'])->map(function ($r) {
            return $r->subdomain . '.' . env('APP_URL');
        })->toArray());

        if ($request->filled('domain')):
            $request->merge([
                'domain' => preg_replace(
                        '/\s+/',
                        '',
                        strtolower($request->input('domain'))) . '.' . env('APP_URL')
            ]);
        endif;

        $rules = [
            'name' => 'required|max:100',
            'phone_code' => 'required|numeric',
            'phone' => 'required|numeric|phone:AUTO,ID|unique:tbl_user_agent,phone',
            'email' => 'nullable|email|unique:tbl_user_agent,email',
            'ownership_status' => 'required|in:personal,corporate',
            'agreement' => 'required',
            'domain' => ['required', 'max:100', 'min:3',
                Rule::unique('tbl_company', 'domain_memoria'),
                Rule::notIn($restricts)],
            'city_id' => 'required|exists:tbl_city,id_city',
            'ref' => 'nullable|exists:tbl_company,referral_code'
        ];
        $this->validate($request, $rules,[
            'name.required'=>trans('custom_validation.name_required'),
            'ownership_status.required'=>trans('custom_validation.business_legality'),
            'phone.required'=>trans('custom_validation.phone_required'),
            'phone.unique'=>trans('custom_validation.phone_unique'),
            'email.email'=>trans('custom_validation.email_valid'),
            'domain.required'=>trans('custom_validation.website_required'),
            'domain.unique'=>trans('custom_validation.website_taken'),
            'city_id.required'=>trans('custom_validation.city_required'),
            'phone.phone'=>trans('custom_validation.invalid_phone_format'),
            'phone_code.required'=>trans('custom_validation.phone_code_required'),
            'agreement.required'=>trans('custom_validation.agreement_required')
        ]);

        try {
            \DB::beginTransaction();
            $newCompany = Company::create([
                'domain_memoria' => $request->domain,
                'company_name' => strip_tags($request->input('name')),
                'email_company' => $request->input('email'),
                'ownership_status' => checkRequestExists($request, 'ownership_status') ? $request->input('ownership_status') : 'personal',
                'logo' => 'dest-operator/img/logo1.png',
                'banner' => 'dest-operator/img/banner1.jpg',
                'phone_company' => $request->input('phone_code') . $request->input('phone'),
                'status' => 1
            ]);
            $newUser = UserAgent::create([
                'first_name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt(str_random(8)),
                'id_company' => $newCompany->id_company,
                'language' => app()->getLocale(),
                'status' => 0,
                'phone' => $request->input('phone'),
                'phone_code' => $request->input('phone_code')
            ]);
            $updates = Update::orderBy('created_at')->get();
            if ($request->filled('ref')):
                $parent = Company::where('referral_code', $request->input('ref'))->first();
                if ($parent) {
                    $newCompany->update(['parent_id' => $parent->id_company]);
                }
            endif;
            $newCompany->updates()->sync($updates);
            $random = generateRandomString(4, 'number');
            $randomLink = generateRandomString(10);
            while (UserOtp::where('otp', $random)->where('type', 'register')->first()) {
                $random = generateRandomString(4, 'number');
            }
            UserOtp::create([
                'user_id' => $newUser->id_user_agen,
                'otp' => $random,
                'shortcode' => $randomLink
            ]);

            \DB::commit();
            if ($request->get('phone_code') == '62'):
                $message = 'Selamat datang di Gomodo. Terima kasih telah bergabung. Masukkan kode verifikasi ' . $random . ' untuk masuk ke dashboard. JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK Tim Gomodo.';
            else:
                $message = 'Welcome to Gomodo. Thank you for registering. Please use this verification code ' . $random . ' to login to your dashboard. DO NOT GIVE this code to anyone, INCLUDING Gomodo Team.';
            endif;
            $number = $request->input('phone_code') . $request->input('phone');
            \DB::commit();
            Woowa::SendMessageSync()->setMessage($message)->setPhone($number)->prepareContent()->send();
            switch (env('SMS_PROVIDER','zenziva')){
                case 'zenziva':
                    $this->sendOTP($number, "#GOMODO Kode OTP : " . $random);
                    break;
                case 'tig':
                    $tigService->sendMessage($number, "#GOMODO Kode OTP : " . $random);
                    break;
            }
            $this->sendOTP($number, "#GOMODO Kode OTP : " . $random);
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
                $content .= "Phone Number  : " . $newUser->phone_code . $newUser->phone . "\n";
            endif;
            $content .= "IP Address    : " . $ip . "\n";
            $content .= "City name     : " . $loc->cityName . "\n";
            $content .= "Region Name   : " . $loc->regionName . "\n";
            $content .= "Country Code  : " . $loc->countryCode . "\n";
            $content .= '```';
            $this->sendDiscordNotification(sprintf('%s', $content), 'onboarding');
            return apiResponse(200, 'OK');
        } catch (\Exception $e) {
            \DB::rollBack();
            return apiResponse(500, $e->getMessage());
            // dd($exception);
        }
    }

    public function requestOTP(Request $request,TigService $tigService)
    {
        $rules = [
            'phone_code' => 'required|numeric',
            'phone' => [
                'required',
                'phone:AUTO,ID',
                Rule::exists('tbl_user_agent', 'phone')->where('phone_code', $request->input('phone_code'))
            ]
        ];
        $this->validate($request, $rules);
        $user = UserAgent::where('phone', $request->phone)->where('phone_code', $request->phone_code)->first();
        if (!$user) {
            return apiResponse(401, 'not found');
        }
        UserOtp::whereUserId($user->id_user_agen)->delete();
        if (!$user->company || $user->company->status != '1') {
            return apiResponse(422, __('auth.suspend'), ['phone' => [__('auth.suspend')]]);
        }
        $random = generateRandomString(4, 'number');
        $randomLink = generateRandomString(10);
        while (UserOtp::where('otp', $random)->where('type', 'register')->first()) {
            $random = generateRandomString(4, 'number');
        }
        UserOtp::create([
            'user_id' => $user->id_user_agen,
            'otp' => $random,
            'shortcode' => $randomLink
        ]);
        if ($request->get('phone_code') == '62'):
            $message = 'Gomodo: Untuk login, masukkan kode verifikasi ' . $random . ' dalam 10 menit. JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK Tim Gomodo.';
        else:
            $message = 'Gomodo: To login, enter the verification code ' . $random . ' in 10 minutes. DO NOT GIVE this code to anyone, INCLUDING Gomodo Team.';
        endif;
        $number = $request->input('phone_code') . $request->input('phone');
        switch (env('SMS_PROVIDER','zenziva')){
            case 'zenziva':
                $this->sendOTP($number, "#GOMODO Kode OTP : " . $random);
                break;
            case 'tig':
                $tigService->sendMessage($number, "#GOMODO Kode OTP : " . $random);
                break;
        }
        Woowa::SendMessageSync()->setMessage($message)->setPhone($number)->prepareContent()->send();
        return apiResponse(200, 'OK');
    }

    public function validateOTP(Request $request)
    {
        $rules = [
            'phone_code' => 'required|numeric',
            'phone' => [
                'required',
                'phone:AUTO,ID',
                Rule::exists('tbl_user_agent', 'phone')->where('phone_code', $request->input('phone_code'))
            ],
            'otp' => 'required|max:4|min:4'
        ];
        $this->validate($request, $rules);
        $user = UserAgent::where('phone', $request->phone)->where('phone_code', $request->phone_code)->first();
        if (!$user) {
            return apiResponse(401, __('auth.incorrect'));
        }
        if (!$user->company || $user->company->status != '1') {
            return apiResponse(422, __('auth.suspend'), ['phone' => [__('auth.suspend')]]);
        }
        $checkOtp = UserOtp::whereUserId($user->id_user_agen)->where('otp', $request->input('otp'))->first();
        if ($checkOtp) {
            UserOtp::whereUserId($user->id_user_agen)->delete();
            auth('web')->loginUsingId($user->id_user_agen, true);
            if ($user->status != '1') {
                $user->update(['status' => 1]);
            }
            return apiResponse(200, 'Logged In');
        }
        return apiResponse(400, 'Otp Invalid');
    }
}
