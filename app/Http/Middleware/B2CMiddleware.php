<?php

namespace App\Http\Middleware;

use App\Models\RestrictSubDomain;
use Closure;

class B2CMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (($request->getHttpHost() == env('APP_URL')) ||
            $request->getHttpHost() == env('KLHK_BACKOFFICE_URL', 'bupsha.'.env('APP_URL')) ||
            $request->getHttpHost() == env('KLHK_DOMAIN', 'persona.'.env('APP_URL'))) {
            return $next($request);
        }
        if ($request->get('my_company') && $request->get('my_company') != 0) {
            return $next($request);
        }
        $newHost = str_replace(env('B2B_DOMAIN'), env('APP_URL'), $request->fullUrl());
        return redirect($newHost);
    }
}
