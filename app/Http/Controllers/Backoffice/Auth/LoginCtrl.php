<?php

namespace App\Http\Controllers\Backoffice\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginCtrl extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/back-office';

    /**
     * LoginCtrl constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * sho login Form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        toastr();
        return viewKlhk('back-office.auth.login', 'new-backoffice.login.index');
    }

    /**
     * determine username from database
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * determine current auth
     * @return mixed
     */
    protected function guard()
    {
        return \Auth::guard('admin');
    }

    /**
     * logout Admin
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('admin:login'));
    }

    /**
     * response Login
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        $redirect = $this->redirectPath();

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($redirect);
    }

    /**
     * action after successfull login
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated(Request $request, $user)
    {
        if (\Session::has('redirect')) {
            $redirect = \Session::get('redirect', route('admin:dashboard'));
            \Session::forget('redirect');
        }

        return redirect(route('admin:dashboard'));
    }

}
