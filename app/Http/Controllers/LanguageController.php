<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * function check get language
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function changeLanguage(Request $request)
    {

        if ($request->has('lang') && in_array($request->get('lang'), ['en', 'id'])) {

            if (auth('web')->check()){

                $user = auth('web')->user();
                $user->update([
                   'language' => $request->get('lang')
                ]);
            }

            $domain = explode('.', $request->getHttpHost());
            if (count($domain) >= 3) {
                array_shift($domain);
            }
            
            setcookie('lang', $request->get('lang'), time() + 31556926, '/', '.'.implode('.', $domain));
            //$request->session()->put('userLang', $request->get('lang'));
        }

        return redirect()->back();
    }
}
