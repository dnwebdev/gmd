<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'xendit/payment',
        'xendit/disbursement',
        '/api_mail',
        'back-office/*',
        'explore/*',
        'company/product/update_ota',
        'developer/midtrans-post'.
        'otp/*',
        'kredivo/callback',
        'ewallet/callback',
        '/sendmail'
    ];

    protected function inExceptArray($request)
    {
        if ($request->is('product/book/*') && $request->has('widget')) {
            return true;
        }

        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
