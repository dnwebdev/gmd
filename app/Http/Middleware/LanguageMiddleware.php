<?php

namespace App\Http\Middleware;

use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Available Language
        $availableLang = ['en', 'id'];
        // Default language (Indonesia)
        $lang = 'id';
        if ($request->has('userLang') && in_array($request->userLang,$availableLang)){
            $lang = $request->userLang;
            setcookie("lang", $request->userLang, time()+2*24*60*60);
        }elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $availableLang)) {
            $lang = $_COOKIE['lang'];
        }

//        if ($request->is('/') || $request->is('explore*')) {
//            $host = $request->getHttpHost();
//            $company = \App\Models\Company::where('domain', $host)->orWhere('domain_memoria', $host)->first();
//            if (!$company) {
//                \App::setLocale($lang);
//                $response = $next($request);
//                header_remove('X-Frame-Options');
//                header_remove('Content-Security-Policy');
//                return $response;
//            }
//        }
//        if (auth('web')->check()) {
//            $user = auth('web')->user();
//            $lang = $user->language;
//        }

        \App::setLocale($lang);
        $response = $next($request);
        if ($request->hasHeader('X-Frame-Options')):
            header_remove('X-Frame-Options');
        endif;
        if ($request->hasHeader('Content-Security-Policy')):
            header_remove('Content-Security-Policy');
        endif;


        return $response;
    }
}
