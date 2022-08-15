<?php

namespace App\Traits;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

trait XenditTrait
{

    private $baseUrl = "https://api.xendit.co/v2/";
    private $baseUrlCreditCard = "https://api.xendit.co/";

    /**
     * function request data to Xendit Payment Gateway
     * @param $type
     * @param $data
     * @param null $invoice_id
     * @return bool|\Exception|string
     */

    private function XenditApi($type, $data, $invoice_id = null, $ewalletType= null)
    {
        $headers = array(
            'Content-Type:application/json'
        );
        switch ($type) {
            case 'invoice':
                $url = $this->baseUrl . 'invoices';
                $method = "POST";
                break;
            case 'credit-card':
                $url = $this->baseUrlCreditCard . 'credit_card_charges';
                $method = "POST";
                break;
            case 'alfamart':
                $url = $this->baseUrlCreditCard . 'fixed_payment_code';
                $data['retail_outlet_name'] = 'ALFAMART';
                $method = "POST";
                break;
            case 'ovo':
                $url = $this->baseUrlCreditCard . 'ewallets';
                $data['ewallet_type'] = 'OVO';
                $method = "POST";
                array_push($headers, 'x-api-version:2020-02-01');
                break;
            case 'get-credit-card-charge':
                $url = $this->baseUrlCreditCard . 'credit_card_charges/' . $invoice_id;
                $method = "GET";
                break;
            case 'get-invoice':
                $url = $this->baseUrl . 'invoices/' . $invoice_id;
                $method = "GET";
                break;
            case 'get-ewallet':
                $url = $this->baseUrlCreditCard . 'ewallets?external_id=' . $invoice_id .'&ewallet_type=' . $ewalletType;
                $method = "GET";
                break;
            case 'cardless-credit':
                $url = $this->baseUrlCreditCard . 'cardless-credit';
                break;
            default:
                $url = $this->baseUrl . 'invoices';
                $method = "POST";

        }

        $key = env("XENDIT_KEY");
        $data = json_encode($data);
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, $key . ":");

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data),
                    $type == 'ovo' ? 'x-api-version:2020-02-01' : '')
            );

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * Xendit Invoice API
     * @param $data
     * @return bool|\Exception|string
     */
    public function XenditInvoiceApi($data)
    {
        return $this->XenditApi('invoice', $data);
    }

    /**
     * Xendit Credit Card Api
     * @param $data
     * @return bool|\Exception|string
     */
    public function XenditCreditCard($data)
    {
        return $this->XenditApi('credit-card', $data);
    }

    public function alfamart($data)
    {

    }

    /**
     * Xendit Kredivo
     * @param array $data
     * @return bool|\Exception|string
     */
    public function XenditKredivo(array $data)
    {
        $data['cardless_credit_type'] = 'KREDIVO';
        return $this->XenditApi('cardless-credit', $data);
    }
}

