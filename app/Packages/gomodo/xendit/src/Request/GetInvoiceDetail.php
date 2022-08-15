<?php
namespace Gomodo\Xendit\Request;
use Gomodo\Xendit\Api;

class GetInvoiceDetail extends Api
{
    protected $baseUrl ='https://api.xendit.co/v2';
    public function __construct($id)
    {
        parent::__construct();
        $this->segment ='invoices/'.$id;
    }
}
