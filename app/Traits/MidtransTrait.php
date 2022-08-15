<?php

namespace App\Traits;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

trait MidtransTrait
{

    private $baseUrl = "https://api.midtrans.com/v2/";
    private $stagingUrl = "https://api.sandbox.midtrans.com/v2/";

    /**
     * function request data to Midtrans Payment Gateway
     * @param $type
     * @param $data
     * @param null $invoice_id
     * @return bool|\Exception|string
     */
    private function MidtransApi($type, $data, $invoice_id=null)
    {
        if (app()->environment() == 'production') {
            $urlApi = $this->baseUrl;
        } else {
            $urlApi = $this->stagingUrl;
        }

        $headers = array(
            'Content-Type:application/json'
        );
        switch ($type) {
            case 'get-order':
                $url = $urlApi .$invoice_id.'/status';
                $method ="GET";
                break;
        }

        
        $data = json_encode($data);
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, env('MIDTRANS_SERVER_KEY') . ":");

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}

