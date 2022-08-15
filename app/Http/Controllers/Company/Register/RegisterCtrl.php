<?php

namespace App\Http\Controllers\Company\Register;

use App\Http\Requests\Api\Auth\ActivateOtp;
use App\Jobs\SendEmail;
use App\Models\Company;
use App\Models\RestrictSubDomain;
use App\Models\Update;
use App\Models\UserAgent;
use App\Models\UserOtp;
use App\Notifications\Register\CompanyRegister;
use App\Notifications\Update\UpdateNewsNotification;
use App\Traits\DiscordTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class RegisterCtrl extends Controller
{

    use DiscordTrait;

    /**
     * function validation first Step
     *
     * @param mixed $request
     *
     * @return void
     */
    public function firstStep(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Not Permitted'], 403);
        }
        $rule = [
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'phone' => 'required|min:6|max:20',
            'email' => 'required|email|unique:tbl_user_agent,email|max:100',
            'password' => 'required|min:6|max:100'
        ];
        $this->validate($request, $rule);
        return response()->json(['message' => 'OK', 'request' => $request->all()]);
    }

    /**
     * Function Save new Company Registration
     *
     * @param mixed $request
     *
     * @return void
     */
    public function registerCompany(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Not Permitted'], 403);
        }
//        $firstRule = [
//            'domain'=>'required|unique:blacklist_sub_domains,subdomain'
//        ];
//        $this->validate($request, $firstRule);
        $subdomain = preg_replace('/\s+/', '', strtolower($request->domain));
        if ($request->has('domain') && $request->domain !== '' && $request->domain !== null) {
            $request->merge(['domain' => preg_replace('/\s+/', '', strtolower($request->domain)) . '.' . env('APP_URL')]);
        }
        $rule = [
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'email' => 'required|email|unique:tbl_user_agent,email|max:100',
            'password' => 'required|min:6|max:100',
            'business_category' => 'required|array|min:1',
            'business_category.*' => 'required|exists:tbl_business_category,id',
            'company_name' => 'required|max:100',
            'domain' => 'required|unique:tbl_company,domain_memoria|max:100|regex:/^[a-z0-9]+.'.env('APP_URL').'$/',
            'agreement' => 'required',
            'ownership_status' => 'required|in:personal,corporate',
            'phone' => 'required',
        ];
        $this->validate($request, $rule);
        if (RestrictSubDomain::whereSubdomain($subdomain)->first() || in_array($subdomain,['pesona','bupsha','klhk'])) {
            return apiResponse(422, '', null, ['domain' => [trans('validation.another', ['attribute' => 'domain'])]]);
        }
        try {
            \DB::beginTransaction();
            $newCompany = Company::create([
                'domain_memoria' => $request->input('domain'),
                'company_name' => $request->input('company_name'),
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
                'status' => 0, //nanti diganti
                'phone' => $request->input('phone'),
            ]);

            foreach ($request->input('business_category') as $categoryId) {
                $newCompany->categories()->attach($categoryId);
            }
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
//            foreach ($updates as $update) {
//                \Notification::send($newCompany, new UpdateNewsNotification($update->id, $update->type, $update->title, $update->content, $update->title_indonesia, $update->content_indonesia, $update->created_at->format('d/m/Y')));
//            }
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
            $content .= "Company Name    : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Login Email     : " . $newCompany->agent->email. "\n";
            $content .= "Phone Number    : " . $newCompany->phone_company. "\n";
            $content .= "IP Address      : " . $ip. "\n";
            $content .= "City Name       : " . $loc->cityName. "\n";
            $content .= "Region Name     : " . $loc->regionName. "\n";
            $content .= "Country Code    : " . $loc->countryCode. "\n";
            $content .= '```';
            $newUser->notifyNow(new CompanyRegister($company_token->token));

            $this->sendDiscordNotification(sprintf('%s', $content), 'onboarding');
//            $this->sendDiscordNotification($content, 'onboarding');

//            \Auth::guard('web')->attempt(['email' => $newUser->email, 'password' => $request->input('password')]);
//            $subject = 'New Provider';
//            $to = 'anindya.dewi@gomodo.tech';
//            $dataEmail['name'] = 'Anindya Dewi';
//            $dataEmail['provider'] = $newCompany;
//            $template = view('mail.register.notifnewregister', $dataEmail)->render();
//            $ccs = ['heri.karisma@gomodo.tech', 'support@mygomodo.com', 'fichainggit@gomodo.tech', 'lw@gomodo.tech'];
//            dispatch(new SendEmail($subject, $to, $template, $ccs));
            Mail::to('anindya.dewi@gomodo.tech')->sendNow(new \App\Mail\Register\NotifToGomodo($newCompany));
            return response()->json(['message' => 'Registration success please check your email', 'redirect' => route('login')], 200);
        } catch (\Exception $exception) {
            \DB::rollBack();
            dd($exception);
        }
    }

    /**
     * function validation email
     *
     * @param mixed $request
     *
     * @return void
     */
    public function validateEmail(Request $request)
    {
        $rule = [
            'email' => 'required|email|unique:tbl_user_agent,email',
        ];
        $this->validate($request, $rule);
        return response()->json(['message' => 'OK']);
    }

    public function validatePhone(Request $request)
    {
        $rule = [
            'phone_code' => 'required|numeric',
            'phone' => 'required|min:8|max:20|unique:tbl_user_agent,phone',
        ];
        $this->validate($request, $rule);
        return response()->json(['message' => 'OK']);
    }

    /**
     * function validation domain
     *
     * @param mixed $request
     *
     * @return void
     */
    public function validateDomain(Request $request)
    {
        $firstRule = [
            'domain' => 'required|alpha_num|min:3|max:100|unique:tbl_company,domain_memoria',
        ];
        $this->validate($request, $firstRule);
        if ($request->has('domain') && $request->domain !== '' && $request->domain !== null) {
            $request->merge(['domain' => $request->domain . '.' . env('APP_URL')]);
        }
        $rule = [
            'domain' => 'required|min:3|unique:tbl_company,domain_memoria|regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
        ];
        $this->validate($request, $rule);
        return response()->json(['message' => 'OK']);
    }

    public function activateUser(Request $request)
    {
        $companyToken = \App\Models\CompanyToken::where('token', $request->get('token'))->whereHas('company', function ($c) use ($request) {
            $c->whereHas('agent', function ($a) use ($request) {
                $a->where('email', $request->get('email'));
            });
        })->first();
        if ($companyToken) {
            if ($companyToken->company->agent->status == '1') {
                msg('Already Active', 2);
                $companyToken->delete();
                return redirect('/');
            }

            if ($companyToken->expired_at < Carbon::now()->toDateTimeString()) {
                msg('Token Expired', 2);
                $companyToken->delete();
                return redirect('/');
            }
            $companyToken->company->agent->update(['status' => 1]);
            msg('Activation Success');
            $companyToken->delete();
            auth('web')->loginUsingId($companyToken->company->agent->id_user_agen);
            return redirect()->route('company.dashboard', ['multipage' => true]);
        }
        msg('Token not Found', 2);
        return redirect('/');
    }

    // OTP ACTIVATION

    public function activateOtp(ActivateOtp $request)
    {
        $userOtp = UserOtp::where('otp', $request->input('otp'))
//            ->where('shortcode', $shortcode)
            ->where('type','register')->first();
        if (!$userOtp){
            return apiResponse(422,trans('validation.invalid_data'),[
                'otp'=>[
                    trans('validation.exists',['attribute'=>'otp'])
                ]
            ]);
        }
        $userOtp->user->update([
            'status'=>1,
            'password'=>bcrypt($request->input('password'))
        ]);
        auth('web')->loginUsingId($userOtp->user->id_user_agen);
        UserOtp::where('user_id',$userOtp->user_id)->where('type','register')->delete();
        return apiResponse(200, 'OK');
    }

    public function viewActivate(Request $request)
    {
//        $where = [
//          'shortcode'=>$shortcode,
//          'type'=>'register'
//        ];
//        $otp = \App\Models\UserOtp::where($where)->first();
//        if (!$otp){
//            msg('OtP not found',2);
//            return redirect('/');
//        }
//        $user = UserAgent::where('id_user_agen',$otp->user_id)->first();
        $phone = $request->phone;
        if ($request->get('klhk') == 1){
            return view('klhk.auth.new-login.activation', compact('phone'));
        }
        return view('auth.new-login.activation', compact('phone'));
    }
}
