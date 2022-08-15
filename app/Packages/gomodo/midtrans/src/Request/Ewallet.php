<?php

namespace Gomodo\Midtrans\Request;

use Gomodo\Midtrans\Api;

class Ewallet extends Api
{
    public function __construct($ewallet)
    {
        parent::__construct($ewallet);
        $this->data = $ewallet;
    }
}