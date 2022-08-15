<?php

namespace Gomodo\Midtrans\Request;

use Gomodo\Midtrans\Api;

class Notification extends Api
{
    public function __construct($notif)
    {
        parent::__construct();
        $this->data = $notif;
    }
}