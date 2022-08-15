<?php

namespace App\Http\Middleware;

use Closure;

class DeveloperSupperAdminMiddleware
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
        if (auth('admin')->check() && auth('admin')->user()->role->role_slug=='super-admin'){
            return $next($request);
        }
        if ($request->ajax()){
            return apiResponse(403,'You Are Not permitted to do that action');
        }
        msg('You dont have permission',2);
        return redirect()->route('admin:dashboard');

    }
}
