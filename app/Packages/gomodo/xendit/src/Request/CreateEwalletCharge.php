<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateEwalletCharge extends Api
{
    protected $baseUrl = "https://api.xendit.co";
    protected $segment = "ewallets";
    protected $method = "POST";

    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }
}
