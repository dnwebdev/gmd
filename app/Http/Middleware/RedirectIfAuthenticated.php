<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            if ($guard =='admin') {
                $klhk_backoffice = $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.' . env('APP_URL'));
                if ($request->getHttpHost() == $klhk_backoffice) {
                    return redirect($klhk_backoffice);
                }
                return redirect('/back-office');
            }
            return redirect('/company/dashboard');
        }

        return $next($request);
    }
}
