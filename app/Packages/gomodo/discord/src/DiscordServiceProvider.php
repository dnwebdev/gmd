<?php
namespace Gomodo\Discord;
use Illuminate\Support\ServiceProvider;
class DiscordServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('Discord', function () {
            return new Notify();
        });
    }


}
