<?php

namespace Gomodo\Midtrans\Request;

use Gomodo\Midtrans\Api;

class GetOrder extends Api
{
    public function __construct($id)
    {
        parent::__construct();
        $this->data = $id;
        $this->segment = $id.'/status';
        if (config('midtrans.isProduction')) {
            $this->baseUrl = "https://api.midtrans.com/v2/";
        } else {
            $this->baseUrl = "https://api.sandbox.midtrans.com/v2/";
        }
    }
}