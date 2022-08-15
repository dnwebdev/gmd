<?php

namespace App\Http\Middleware;

use App\Models\LoginLog;
use App\Models\RestrictSubDomain;
use Carbon\Carbon;
use Closure;

class ActiveProviderMiddleware
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
        if (auth('web')->check()) {
            if (auth('web')->user()->status == '0' || auth('web')->user()->company == null) {
                auth('web')->logout();
                if ($request->ajax()) {
                    return apiResponse(401, 'Unauthenticated, Your Account is not active yet');
                }
                msg('User Suspended', 2);
                return redirect('/agent/login');

            } elseif (auth('web')->user()->company->status == '0') {
                auth('web')->logout();
                msg('Company Suspended', 2);
                if ($request->ajax()) {
                    return apiResponse(401, 'Your account is suspended');
                }
                return redirect('/agent/login');
            } elseif (auth('web')->user()->company->domain_memoria) {
                $subdomain = explode('.'.env('APP_URL'), auth('web')->user()->company->domain_memoria)[0];

                if (RestrictSubDomain::whereSubdomain($subdomain)->first()) {
                    auth('web')->logout();
                    msg('Company Suspended', 2);
                    if ($request->ajax()) {
                        return apiResponse(401, 'Your account is suspended');
                    }
                    return redirect('/agent/login');
                }
            }

            if (auth('web')->user()->company->is_klhk == '1') {
                $request->attributes->add(['klhk' => true]);
                $data = [
                    'last_login' => Carbon::now()->toDateString(),
                    'company_id' => auth('web')->user()->company->id_company
                ];
                LoginLog::firstOrCreate($data);
                return $next($request);
            } else {
                $request->attributes->add(['klhk' => false]);
                $data = [
                    'last_login' => Carbon::now()->toDateString(),
                    'company_id' => auth('web')->user()->company->id_company
                ];
                LoginLog::firstOrCreate($data);
                return $next($request);
            }
        }

    }
}
