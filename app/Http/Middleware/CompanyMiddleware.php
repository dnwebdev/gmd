<?php

namespace App\Http\Middleware;

use Closure;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHttpHost();
        if ($host != env('APP_URL') && $host != env('KLHK_DOMAIN')){
            $newHost = str_replace($host, env('APP_URL'), $request->fullUrl());

            return redirect($newHost);
        }
        return $next($request);
    }
}
