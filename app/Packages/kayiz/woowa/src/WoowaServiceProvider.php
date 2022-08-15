<?php
namespace Kayiz;
use Illuminate\Support\ServiceProvider;
class WoowaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Woowa', function () {
            return new Woowa();
        });
        $this->mergeConfigFrom(__DIR__.'/config/woowa.php', 'woowa');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/config/woowa.php' =>   config_path('woowa.php'),
                __DIR__.'/resources/lang/' => resource_path('/lang')
            ], 'config');
            $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'woowa');
        }
    }
}
