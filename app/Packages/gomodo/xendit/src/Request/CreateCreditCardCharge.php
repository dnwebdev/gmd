<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateCreditCardCharge extends Api
{
    protected $method = "POST";
    protected $baseUrl = "https://api.xendit.co";
    protected $segment = "credit_card_charges";
    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }

}
