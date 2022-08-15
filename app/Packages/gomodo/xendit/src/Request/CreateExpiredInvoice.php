<?php
namespace Gomodo\Xendit\Request;
use Gomodo\Xendit\Api;

class CreateExpiredInvoice extends Api
{
    protected $method = "POST";
    protected $baseUrl = "https://api.xendit.co";
    protected $segment = "invoices";
    public function __construct(string $invoice_id)
    {
        parent::__construct();
        $this->segment = 'invoices/'.$invoice_id.'/expire!';

    }
}