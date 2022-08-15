<?php
namespace Gomodo\Xendit\Request;
use Gomodo\Xendit\Api;

class GetQrCodes extends Api
{
    protected $baseUrl ='https://api.xendit.co';

    public function __construct($id)
    {
        parent::__construct();
        $this->segment ='qr_codes/'.$id;
    }
}
