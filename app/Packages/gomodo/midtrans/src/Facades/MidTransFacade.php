<?php

use Illuminate\Support\Facades\Facade;

class MidTransFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Midtrans';
    }
}