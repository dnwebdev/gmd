<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        // Pindah diatas, sebelum web
        $this->mapAdminRoutes();

        $this->mapWebRoutes();
        $this->mapDeveloperRoutes();
        $this->mapExploreRoutes();
        $this->mapPartnerRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.' . env('APP_URL'));
        $domain = env('APP_URL');
        $path = 'back-office';

        if (request()->getHttpHost() == $klhk_backoffice) {
            $domain = $klhk_backoffice;
            $path = '/';
        }

        Route::middleware('web')
            ->namespace($this->namespace.'\Backoffice')
            ->prefix($path)
            ->domain($domain)
            ->group(base_path('routes/backoffice.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapPartnerRoutes()
    {
        Route::prefix('partner/api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->domain(env('PUBLIC_API_URL', env('APP_URL','api.kayiz.com')))
            ->group(base_path('routes/partner.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapDeveloperRoutes()
    {
        Route::prefix('developer')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/developer.php'));
    }
    protected function mapExploreRoutes()
    {
        Route::prefix('explore')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/explore.php'));
    }
}
