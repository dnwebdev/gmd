<?php


namespace Gomodo\Xendit;


use Gomodo\Xendit\Request\CreateCardLessCredit;
use Gomodo\Xendit\Request\CreateEwalletCharge;
use Gomodo\Xendit\Request\CreateExpiredInvoice;
use Gomodo\Xendit\Request\CreateInvoice;
use Gomodo\Xendit\Request\GetBalance;
use Gomodo\Xendit\Request\GetInvoiceDetail;
use Gomodo\Xendit\Request\GetNotification;
use Gomodo\Xendit\Request\CreateQrCodes;
use Gomodo\Xendit\Request\CreateQrCodesSimulate;
use Gomodo\Xendit\Request\GetQrCodes;

class Xendit
{
    public static function GetBalance()
    {
        return new GetBalance();
    }

    public static function GetInvoiceDetail($id)
    {
        return new GetInvoiceDetail($id);
    }

    public static function CreateInvoice($data)
    {
        return new CreateInvoice($data);
    }

    public static function CreateCardLessCredit($data)
    {
        return new CreateCardLessCredit($data);
    }

    public static function Callback()
    {
        return new GetNotification();
    }

    public static function CreateEWallet($data)
    {
        return new CreateEwalletCharge($data);
    }

    public static function CreateQrCodes($data)
    {
        return new CreateQrCodes($data);
    }

    public static function GetQrCodes($id)
    {
        return new GetQrCodes($id);
    }

    public static function CreateQrCodesSimulate($id)
    {
        return new CreateQrCodesSimulate($id);
    }

    public function makeExpiredInvoice($id)
    {
        return new CreateExpiredInvoice($id);
    }
}
