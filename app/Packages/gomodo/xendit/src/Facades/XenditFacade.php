<?php
namespace Gomodo\Xendit\Facades;
use Gomodo\Xendit\Xendit;
use Illuminate\Support\Facades\Facade;

class XenditFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Xendit';
    }
}
