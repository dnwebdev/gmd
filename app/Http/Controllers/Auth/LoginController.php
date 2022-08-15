<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/company/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * action after user successfull login
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated(Request $request, $user)
    {
        
        $request->attributes->add(['my_company' => $user->id_company]);
        $redirect = '/company/dashboard';
        if (\Session::has('redirect')) {
            $redirect = \Session::get('redirect','/company/dashboard');
            \Session::forget('redirect');
        }

        return redirect($redirect);
    }
    public function username()
    {
        if (\request()->has('email')){
            return 'email';
        }
        return 'phone';

    }


}
