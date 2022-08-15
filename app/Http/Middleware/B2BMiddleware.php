<?php

namespace App\Http\Middleware;

use Closure;

class B2BMiddleware
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
        if ($request->getHttpHost() == env('B2B_DOMAIN',env('APP_URL')) || $request->isXmlHttpRequest()) {
            return $next($request);
        }

        return redirect(str_replace($request->getHttpHost(),env('B2B_DOMAIN',env('APP_URL')),$request->fullUrl()));
    }
}
