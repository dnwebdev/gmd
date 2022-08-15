<?php

namespace Gomodo\Midtrans;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class Api
{
    protected $baseUrl;
    protected $apiKey;
    protected $receivedKey;
    protected $segment;
    protected $data = [];
    protected $method = 'GET';
    protected $params = [];
    protected $headers = [];
    protected $signature = '';

    public function __construct()
    {
        $this->serverKey = config('midtrans.serverKey');
        $this->clientKey = config('midtrans.clientKey');
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic '.base64_encode($this->serverKey.':'),
        ];
        if (config('midtrans.isProduction')) {
            $this->baseUrl = 'https://app.midtrans.com/snap/v1/transactions';
        } else {
            $this->baseUrl = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        }
    }

    public function getNotification()
    {
        if (isset($this->data['order_id']) && isset($this->data['status_code']) && isset($this->data['gross_amount'])) {
            $id = $this->data['order_id'];
            $status = $this->data['status_code'];
            $gross_amount = $this->data['gross_amount'];
            $signature = !empty($this->data['signature_key']);
            $raw = $id.$status.$gross_amount.$this->serverKey;

            if (isset($this->data['transaction_id'])){
                if (!empty($signature)){
                    $this->signature = openssl_digest($raw, 'sha512');
                    if ($signature === $this->signature) {
                        if ($this->data['status_code'][0] == '2') {
                            if (isset($this->data['store'])) {
                                return json_decode(json_encode([
                                    'error' => false,
                                    'message' => \trans('midtrans_notification.'.$this->data['status_code'].'.'.$this->data['payment_type'].'.'.$this->data['store']),
                                    'data' => $this->data
                                ]));
                            }
                            return json_decode(json_encode([
                                'error' => false,
                                'message' => \trans('midtrans_notification.'.$this->data['status_code'].'.'.$this->data['payment_type']),
                                'data' => $this->data
                            ]));
                        }
                        return json_decode(json_encode([
                            'error' => false,
                            'data' => $this->data
                        ]));
                    }
                }

                if ($this->data['status_code'][0] == '2') {
                    if (isset($this->data['store'])) {
                        return json_decode(json_encode([
                            'error' => false,
                            'message' => \trans('midtrans_notification.'.$this->data['status_code'].'.'.$this->data['payment_type'].'.'.$this->data['store']),
                            'data' => $this->data
                        ]));
                    }
                    return json_decode(json_encode([
                        'error' => false,
                        'message' => \trans('midtrans_notification.'.$this->data['status_code'].'.'.$this->data['payment_type']),
                        'data' => $this->data
                    ]));
                }
                return json_decode(json_encode([
                    'error' => false,
                    'data' => $this->data
                ]));

            }

            return json_decode(json_encode([
                'error' => true,
                'data' => null,
                'message' => 'Something Wrong'
            ]));
        }
        return json_decode(json_encode([
            'error' => true,
            'data' => null,
            'message' => 'Invalid Order'
        ]));
    }

    public function send()
    {
        $data = json_encode($this->data);
        $this->headers = Arr::add($this->headers, 'Content-Length', strlen($data));
        $headers = Arr::flatten(collect($this->headers)->map(function ($value, $key) {
            return [$key.':'.$value];
        })->toArray());
        try {
            $url = $this->baseUrl;
            if (strlen($this->segment) > 0) {
                $url .= '/'.$this->segment;
            }
            if (count($this->params) > 0) {
                $url .= '?'.http_build_query($this->params);
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey.":");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            if (!isset($result->error_code)) {
                return json_decode(json_encode([
                    'error' => false,
                    'data' => $result
                ]));
            }
            return json_decode(json_encode([
                'error' => true,
                'data' => null,
                'code' => $result->error_code,
                'message' => $result->message
            ]));
        } catch (\Exception $exception) {
            return json_decode(json_encode([
                'error' => true,
                'data' => $exception->getMessage()
            ]));
        }
    }
}

