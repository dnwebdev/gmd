<?php

namespace App\Http\Middleware;

use Closure;

class CheckHost
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
//        $host = $request->getHttpHost();
//        if($host != env("APP_URL")){
//            //return redirect()->intended('http://'.env("APP_URL"));
//            abort(404);
//        }
//
        return $next($request);
    }
}
