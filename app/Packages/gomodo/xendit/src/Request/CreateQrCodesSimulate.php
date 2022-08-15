<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateQrCodesSimulate extends Api
{
    protected $baseUrl = "https://api.xendit.co";
    protected $method = "POST";

    public function __construct($id)
    {
        parent::__construct();
        $this->segment = 'qr_codes/'.$id.'/payments/simulate';
    }
}
