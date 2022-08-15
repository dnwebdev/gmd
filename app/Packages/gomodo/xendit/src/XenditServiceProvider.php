<?php

namespace Gomodo\Xendit;

use Illuminate\Support\ServiceProvider;

class XenditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Xendit', function () {
            return new Xendit();
        });
        $this->mergeConfigFrom(__DIR__.'/../config/xendit.php', 'xendit');
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
                __DIR__.'/../config/xendit.php' =>   config_path('xendit.php'),
            ], 'config');

        }
    }
}
