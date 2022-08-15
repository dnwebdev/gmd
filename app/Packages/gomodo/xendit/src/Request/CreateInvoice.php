<?php


namespace Gomodo\Xendit\Request;


use Gomodo\Xendit\Api;

class CreateInvoice extends Api
{
    protected $baseUrl='https://api.xendit.co/v2';
    protected $segment = 'invoices';
    protected $method = 'POST';
    public function __construct(array $data)
    {
        parent::__construct();
        $this->data = $data;
    }
}
