<?php


namespace App\Services;


class TigService
{
    private $url;
    private $data = [];

    public function __construct()
    {
        $this->data["password"] = env("TIG_APP_PASSWORD", "upsEef6lj");
        $this->data["account"] = env("TIG_APP_ID_REGULER", "I2606507");
    }

    private function resetProperty()
    {
        $this->data = [];
        $this->data["password"] = env("TIG_APP_PASSWORD", "upsEef6lj");
        $this->data["account"] = env("TIG_APP_ID_REGULER", "I2606507");
    }

    public function checkBalance()
    {
        $this->resetProperty();
        $this->url = "http://intapi.253.com/balance/json";
        $result['url'] = $this->url;
        $result['postData'] = $this->data;
        $result['result'] = $this->api();
        return $result;

    }

    public function pullReport($count = 20)
    {
        $this->resetProperty();
        $this->url = "http://intapi.253.com/pull/report?";
        $this->data['count'] = $count;
        $result['url'] = $this->url;
        $result['postData'] = $this->data;
        $result['result'] = $this->api();
        return $result;
    }

    public function sendMessage($phone,$message)
    {
        $this->resetProperty();
        $this->data['msg'] = $message;
        $this->data['mobile'] = $phone;
        $this->url = "http://intapi.253.com/send/json?";
        $result['url'] = $this->url;
        $result['postData'] = $this->data;
        $result['result'] = $this->api();
        return $result;
    }

    private function api()
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'
            )
        );
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($this->data));
        $results = json_decode(curl_exec($curlHandle), true);
        return $results;
    }
}