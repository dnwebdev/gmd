<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAgent;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function showLinkRequestForm(Request $request)
    {
        toastr();
        if ($request->get('klhk') == 1){
            return view('klhk.auth.new-login.forgot_password_mail');
        }
        return view('auth.new-login.forgot_password_mail');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $user = UserAgent::where('email', $request->get('email'))->first();
        if (!$user){
            return apiResponse(403, __('general.whoops'), ['email' => [__('auth.email')]]);
        }
        if ($request->get('klhk') && $user->company->is_klhk != 1) {
                return apiResponse(403, __('general.whoops'), ['email' => [__('auth.email')]]);
        }
        $response = $this->broker()->sendResetLink($request->only('email'));

        return $response == \Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkResponse($response)
    {
        if (\request()->wantsJson() || \request()->isXmlHttpRequest()){
            return apiResponse(200,trans('general.success'));
        }
        return back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if (\request()->wantsJson() || \request()->isXmlHttpRequest()){
            return apiResponse(422,trans('general.whoops'),['email'=>[trans($response)]]);
        }
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }
}
