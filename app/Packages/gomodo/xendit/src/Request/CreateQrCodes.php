<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateQrCodes extends Api
{
    protected $baseUrl = "https://api.xendit.co";
    protected $segment = "qr_codes";
    protected $method = "POST";

    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }
}
