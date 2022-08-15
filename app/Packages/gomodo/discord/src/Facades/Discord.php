<?php
namespace Gomodo\Discord\Facades;
use Illuminate\Support\Facades\Facade;

class Discord extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Discord';
    }
}
