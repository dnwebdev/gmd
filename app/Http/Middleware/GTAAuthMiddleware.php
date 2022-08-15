<?php

namespace App\Http\Middleware;

use Closure;

class GTAAuthMiddleware
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
        if(!empty($request->header('token')) && $request->header('token') === config('gta.auth_key')){
           return $next($request);
        }
        
        return apiResponse(401, 'Unauthorized ');
    }
}
