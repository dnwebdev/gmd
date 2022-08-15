<?php

namespace Gomodo\Midtrans;

use Gomodo\Midtrans\Request\GetOrder;
use Gomodo\Midtrans\Request\Notification;
use Gomodo\Midtrans\Request\Pay;

class MidTrans{
    public static function pay($data)
    {
        return new Pay($data);
    }

    public static function notification($data)
    {
        return new Notification($data);
    }

    public static function getorder($data)
    {
        return new GetOrder($data);
    }

}