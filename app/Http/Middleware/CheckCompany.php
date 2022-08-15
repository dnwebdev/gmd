<?php

namespace App\Http\Middleware;

use App\Models\RestrictSubDomain;
use Closure;

class CheckCompany
{

    /**
     * Handle an incoming request.
     *
     * @param  mixed  $request
     * @param  mixed  $next
     *
     * @return void
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHttpHost();

        // Jika back office klhk
        $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.'.env('APP_URL'));
        if ($host == $klhk_backoffice) {
            $request->request->add(['is_klhk' => true]);
            return $next($request);
        }
        $klhk_directory = env('KLHK_DOMAIN', 'persona.'.env('APP_URL'));
        if ($host == $klhk_directory) {
            $request->attributes->add(['klhk' => true]);
            $request->attributes->add(['my_company' => 0]);
            session([
                'my_company' => 0,
                'company_name' => 'KLHK',
                'company_icon' => asset('explore-assets/images/logo-pesona.png'),
                'company_logo' => asset('explore-assets/images/logo-pesona.png')
            ]);
            return $next($request);
        }

        $company = \App\Models\Company::where('domain', $host)->orWhere('domain_memoria', $host)->first();

        if ($company) {
            $subdomain = explode('.'.env('APP_URL'), $company->domain_memoria)[0];

            if (RestrictSubDomain::whereSubdomain($subdomain)->first()) {
                if ($request->ajax()) {
                    return apiResponse(401, 'This Domain is not available');
                }
                if (request()->isSecure()) {
                    return redirect('https://'.env('APP_URL'));
                }
                return redirect('http://'.env('APP_URL'));
            }
            $request->attributes->add(['my_company' => $company->id_company, 'my_host' => $company->domain]);

            if ($company->is_klhk == true) {
                $request->attributes->add(['klhk' => true]);
            } else {
                $request->attributes->add(['klhk' => false]);
            }

            if (is_file(public_path('uploads/company_logo/'.$company->logo))) {
                $company_logo = asset('uploads/company_logo/'.$company->logo.'?date='.time());
                $company_icon = $company_logo;
            } else {
                $company_icon = asset('landing-page/assets/icons/favicon.ico');
                $company_logo = asset('landing-page/assets/images/logo.png');
            }
            session([
                'my_company' => $company->id_company,
                'company_name' => $company->company_name,
                'company_icon' => $company_icon,
                'company_logo' => $company_logo
            ]);

            return $next($request);
        } else {

            if (env('B2B_DOMAIN') != env('APP_URL') && preg_match('/[\s\S].'.env('B2B_DOMAIN').'/', $host)) {
                $host = str_replace('.'.env('B2B_DOMAIN'), '.'.env('APP_URL'), $host);
                return redirect('http://'.$host);
            }


            $subdomain = explode('.'.env('APP_URL'), $host);
            if (count($subdomain) > 1) {
                return redirect('http://'.env('APP_URL'));
            }
            $request->attributes->add(['klhk' => false]);
            $request->attributes->add(['my_company' => 0]);
            session([
                'my_company' => 0,
                'company_name' => 'Gomodo',
                'company_icon' => asset('landing-page/assets/icons/favicon.ico'),
                'company_logo' => asset('landing-page/assets/images/logo.png')
            ]);

            return $next($request);
        }

    }


}
