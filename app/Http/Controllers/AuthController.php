<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Notifications\Register\CompanyRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use App\Jobs\SendEmail;

//use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    protected $redirectTo = '/company/dashboard';

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('host');
        //$this->middleware('company');
        $this->middleware('guest')->except('agent_logout');
    }


    public function agent_register(Request $request)
    {
        $host = \request()->getHttpHost();
        $currentUrl = \request()->fullUrl();
        if (env('APP_URL') != env('B2B_DOMAIN')):
            if ($host == env('B2B_DOMAIN')) {
                return redirect(Str::replaceFirst(env('B2B_DOMAIN'), env('APP_URL'), $currentUrl));
            }
        endif;
        if ($request->get('klhk') == true) {
            return view('klhk.auth.new-login.register');
        }
        $local = app()->getLocale();
        return view('auth.new-login.register',compact('local'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function agent_register_submit(\App\Http\Requests\AgentRegistrationForm $request)
    {
        \DB::transaction(function () use ($request) {
            $company_name = strip_tags($request->input('company_name'));
            $domain = $request->input('domain');
            $domain_memoria = $request->input('domain_memoria');

            $newcompany = \App\Models\Company::create([
                'company_name' => $company_name,
                'domain' => $domain,
                'domain_memoria' => $domain_memoria,
            ]);

            $token = sha1(date('Y-m-d H:i:s') . $company_name);
            $token_expired = date("Y-m-d H:i:s", time() + (365 * 24 * 60 * 60));
            $company_token = \App\Models\CompanyToken::create([
                'token' => $token,
                'status' => 1,
                'id_company' => $newcompany->id_company,
                'expired_at' => $token_expired
            ]);

            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');

            $utility = app('\App\Services\UtilityService');

            $phone = $utility->format_phone($request->input('phone'));
            $password = $request->input('password');
            $newagent = \App\Models\UserAgent::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'status' => 0,
                'password' => bcrypt($password),
                'id_company' => $newcompany->id_company
            ]);


            //Set Themes
            $theme = \App\Models\Theme::first();
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
                'id_company' => $newcompany->id_company,
                'id_theme' => $theme->id_theme,
            ]);


            $email_view_data = ['token' => $token];
            $subject = "Verify Email Address";
            $to = $email;
            $template = view('public.memoria.registrationverificationemail', $email_view_data)->render();

            dispatch(new SendEmail($subject, $to, $template));
        });


        //echo json_encode(['status'=>200,'message'=>'OK']);
        $request->session()->flash('message',
            'Before start your transaction you have to activate your account. The Activation link has been set to your email. ');
        return redirect()->intended('agent/registration/status');
    }

    /**
     * see registration status
     *
     * @return void
     */
    public function registration_status()
    {
        return view('public.memoria.registration_status');
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

    /**
     * process agent login submit
     *
     * @param mixed $request
     *
     * @return void
     */
    public function agent_login_submit(Request $request)
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
                    return apiResponse(200, __('general.success'), ['redirect' => '/company/dashboard']);
                }
                return redirect()->intended($redirect);
            }
        }

        if ($request->wantsJson() || $request->isXmlHttpRequest()) {
            return apiResponse(422, __('general.whoops'), ['email' => [__('auth.incorrect')]]);
        }
        return $this->agent_login()->withErrors(['login' => __('auth.incorrect')]);

    }

    /**
     * process agent logout
     *
     * @param mixed $request
     *
     * @return void
     */
    public function agent_logout(Request $request)
    {
        Auth::guard()->logout();
        session()->invalidate();
        session()->regenerate();
        return redirect()->intended('agent/login');
    }

    /**
     * process agent activation
     *
     * @return void
     */
    public function agent_activation()
    {
        $token = \Request::segment(3);
        $errors = ['invalid_link' => 'Activation Link is invalid'];

        if (!empty($token)) {
            $company_token = \App\Models\CompanyToken::where(['token' => $token, 'status' => 1])->first();

            if ($company_token) {
                if (strtotime(date('Y-m-d H:i:s')) < strtotime($company_token->expired_at)) {
                    $errors = [];

                    $company = \App\Models\Company::find($company_token->id_company);
                    $company->status = 1;
                    $company->save();

                    $user_agent = \App\Models\UserAgent::where(['id_company' => $company->id_company])->first();
                    $user_agent->status = 1;
                    $user_agent->save();

                    $company_token->status = 0;
                    $company_token->save();


                } else {
                    $errors = ['expired' => 'Activation Link has expired'];
                }
            }


        }

        return view('public.memoria.activation_result')->withErrors($errors);
    }

}
