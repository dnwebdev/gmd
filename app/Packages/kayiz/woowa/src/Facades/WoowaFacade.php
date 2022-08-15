<?php
namespace Kayiz\Facades;
use Illuminate\Support\Facades\Facade;

class WoowaFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Woowa';
    }
}
