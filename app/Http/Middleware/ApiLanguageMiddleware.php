<?php

namespace App\Http\Middleware;

use Closure;

class ApiLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language') && in_array(strtolower($request->header('Accept-Language')), ['id', 'en'])):
            app()->setLocale($request->header('Accept-Language'));
        endif;
        return $next($request);
    }
}
