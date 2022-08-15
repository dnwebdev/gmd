<?php

namespace Gomodo\Midtrans;

use Illuminate\Support\ServiceProvider;
use Gomodo\Midtrans\MidTrans;

class MidTransServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Midtrans', function () {
            return new MidTrans();
        });
        $this->mergeConfigFrom(__DIR__.'/../config/midtrans.php', 'midtrans');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/midtrans.php' =>   config_path('midtrans.php'),
                __DIR__.'/../resources/lang/' => resource_path('/lang')
            ], 'config');
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'midtrans_notification');
        }
    }
}
