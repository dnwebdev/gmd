<?php


namespace Gomodo\Xendit\Request;
use Gomodo\Xendit\Api;

class GetBalance extends Api
{
    protected $baseUrl ='https://api.xendit.co/';
    protected $segment='balance';
}
