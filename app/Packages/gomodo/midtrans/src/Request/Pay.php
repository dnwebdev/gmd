<?php

namespace Gomodo\Midtrans\Request;

use Gomodo\Midtrans\Api;

class Pay extends Api
{
    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
        $this->method = 'POST';
    }
}