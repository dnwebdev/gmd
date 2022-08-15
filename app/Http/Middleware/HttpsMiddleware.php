<?php

namespace App\Http\Middleware;

use App;
use Closure;

class HttpsMiddleware
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
        if (!$request->secure() && env('HTTPS',false)==true) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
