<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\UserAgent;
use App\Models\UserOtp;
use App\Notifications\Register\CompanyRegister;
use App\Services\TigService;
use App\Traits\DiscordTrait;
use App\Traits\ZenzivaTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Kayiz\Woowa;
use Laravel\Passport\Client as PassportClient;

class LoginController extends Controller
{
    use DiscordTrait, ZenzivaTrait;
    public function login(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                Rule::exists('tbl_user_agent', 'email')->where('status', '1')
            ],
            'password' => 'required'
        ];
        $this->validate($request,$rules);
        $client = PassportClient::where('password_client', 1)->first();
        if (!$client) {
            return apiResponse(500, 'Passport Auth not Configured');
        }
        $user = UserAgent::where('email',$request->input('email'))->first();
        if(!$user){
            return apiResponse(404, 'User Not Found');
        }
        if (!\Hash::check($request->input('password'),$user->password)){
            return apiResponse(401, 'Unauthenticated');
        }

        $http = new Client();
        $baseURL = env('BASE_API_URL',env('APP_URL','http://localhost'));
        $response = $http->post($baseURL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => $request->input('password'),
                'scope' => '',
            ],
        ]);
        return apiResponse(200, 'OK', json_decode((string) $response->getBody(), true));
    }

    public function loginDefault(Request $request)
    {
        $rules = [
            'email' => $request->get('use') == 0 ? 'required|email|max:100' : 'nullable',
            'telphone' => $request->get('use') == 1 ? 'required|min:8|max:20' : 'nullable',
            'password' => 'required|min:6',
        ];
        $message = [];
        if ($request->get('use') == 1) {
            $message = [
                'dataLogin.required' => \trans('auth.validation.telp.required')
            ];
        } else {
            $message = [
                'dataLogin.required' => \trans('auth.validation.email.required')
            ];
        }
        $this->validate($request, $rules, $message);
        $email = $request->input('email');
        $telphone = $request->get('telphone');
        $password = $request->input('password');
//        $remember = $request->input('remember') ? true : false;
        $remember = 1;
        $use = $request->get('use') == 1;
        $user = \App\Models\UserAgent::when($use, function ($query) use ($telphone) {
            return $query->where('phone', $telphone);
        }, function ($query) use ($email) {
            return $query->where('email', $email);
        })->first();
        if (!$user) {
            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return apiResponse(422, __('general.whoops'), ['email' => [__('auth.incorrect')]]);
            }
            return $this->agent_login()->withErrors(['login' => __('auth.incorrect')]);
        }

        if ($user->status != 1 && $user->company) {
            $company_token = \App\Models\CompanyToken::create([
                'token' => generateRandomString(32),
                'status' => 1,
                'id_company' => $user->company->id_company,
                'expired_at' => Carbon::now()->addDay(7)
            ]);
            $user->notifyNow(new CompanyRegister($company_token->token));
            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return apiResponse(422, __('general.whoops'), ['email' => [__('auth.not_active')]]);
            }
            return $this->agent_login()->withErrors(['login' => __('auth.not_active')]);
        } elseif (!$user->company || $user->company->status !== 1) {
            if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                return apiResponse(422, __('general.whoops'), ['email' => [__('auth.suspend')]]);
            }
            return $this->agent_login()->withErrors(['login' => __('auth.suspend')]);
        } else {
            $value = $use == 1 ? $telphone : $email;
            $key = $use == 1 ? 'phone' : 'email';
            if ($request->get('klhk') && $user->company->is_klhk != 1) {
                return apiResponse(422, __('general.whoops'), ['email' => [__('auth.incorrect')]]);
            }
            if (Auth::guard()->attempt([$key => $value, 'password' => $password],
                $remember)) {
                //                $user = \App\Models\UserAgent::where(['email'=>$email])->first();
                $company_logo = asset('uploads/company_logo/' . $user->company->logo . '?date=' . time());
                $company_icon = $company_logo;


                session([
                    'company_name' => $user->company->company_name,
                    'company_icon' => $company_icon,
                    'company_logo' => $company_logo,
                    'logo_path' => $user->company->logo
                ]);


                $redirect = '/company/dashboard';

                if ($request->wantsJson() || $request->isXmlHttpRequest()) {
                    return apiResponse(200, __('general.success'), ['api_key' => $user->api_token]);
                }
                return redirect()->intended($redirect);
            }
        }

        if ($request->wantsJson() || $request->isXmlHttpRequest()) {
            return apiResponse(422, __('general.whoops'), ['email' => [__('auth.incorrect')]]);
        }
        return $this->agent_login()->withErrors(['login' => __('auth.incorrect')]);
    }

    public function agent_login()
    {
        $host = \request()->getHttpHost();
        $currentUrl = \request()->fullUrl();
        if (env('APP_URL') != env('B2B_DOMAIN')):
            if ($host == env('B2B_DOMAIN')) {
                return redirect(Str::replaceFirst(env('B2B_DOMAIN'), env('APP_URL'), $currentUrl));
            }
        endif;
        toastr();
        if (request()->get('klhk') == true) {
            return view('klhk.auth.new-login.login');
        }
        return view('auth.new-login.login');
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
            if ($user->status != '1') {
                $user->update(['status' => 1]);
            }
            return apiResponse(200, 'Logged In',['api_key'=>$user->api_token]);
        }
        return apiResponse(400, 'Otp Invalid');
    }
}
