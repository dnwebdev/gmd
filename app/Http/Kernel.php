<?php

namespace App\Http;

use App\Http\Middleware\ActiveProviderMiddleware;
use App\Http\Middleware\ApiLanguageMiddleware;
use App\Http\Middleware\B2BMiddleware;
use App\Http\Middleware\B2CMiddleware;
use App\Http\Middleware\CheckCompany;
use App\Http\Middleware\CheckHost;
use App\Http\Middleware\CompanyMiddleware;
use App\Http\Middleware\DeveloperSupperAdminMiddleware;
use App\Http\Middleware\GTAAuthMiddleware;
use App\Http\Middleware\HttpsMiddleware;
use App\Http\Middleware\JSONMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Barryvdh\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
//        HttpsMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            //\Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            LanguageMiddleware::class,
            CheckCompany::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            'localization'

        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'json' => JSONMiddleware::class,
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'company' => \App\Http\Middleware\CheckCompany::class,
        'host' => \App\Http\Middleware\CheckHost::class,
        'language' => LanguageMiddleware::class,
        //'cors'=>HandleCors::class,
        'superadmin' => DeveloperSupperAdminMiddleware::class,
        'active' => ActiveProviderMiddleware::class,
        'admin' => RoleMiddleware::class,
        'cors' => \App\Http\Middleware\Cors::class,
        'localization' => ApiLanguageMiddleware::class,
        'b2b' => B2BMiddleware::class,
        'b2c' => B2CMiddleware::class,
        'b2c_company' => CompanyMiddleware::class,
        'gta_auth'  => GTAAuthMiddleware::class
    ];

    protected $middlewarePriority = [
        JSONMiddleware::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Auth\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
