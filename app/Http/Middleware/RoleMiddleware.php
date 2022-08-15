<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Role;
use Auth;
use Closure;
use Illuminate\Validation\UnauthorizedException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle( $request, Closure $next ,$role)
    {


        if (Auth::guard('admin')->guest()) {
            return redirect()->route('admin:login');
        }
        $roles = is_array($role)
            ? $role
            : explode('|', $role);
        $roles[] = 'super-admin';
        $check = Admin::whereId(auth('admin')->id())->whereHas('role', function ($role) use ($roles){
            $role->whereIn('role_slug',$roles);
        })->first();
        if ($check){
            return $next($request);
        }
        msg('Not Permitted',2);
        return redirect()->route('admin:dashboard');

    }

}
