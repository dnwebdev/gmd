<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateCardLessCredit extends Api
{
    protected $method = "POST";
    protected $baseUrl = "https://api.xendit.co";
    protected $segment = "cardless-credit";
    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }

}
