<?php

namespace Gomodo\Xendit;

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

    public function __construct()
    {
        $this->apiKey = config('xendit.api_key');
        $this->receivedKey = config('xendit.callback_token');
        $this->headers = [
            'Content-Type' => 'application/json'
        ];
    }

    protected function addData(array $array)
    {
        $this->data = array_merge($this->data,$array);
        return $this;
    }

    protected function addHeaders(array $array)
    {
        $this->headers = array_merge($this->headers,$array);
        return $this;
    }

    protected function overwriteSegment($segment){
        $this->segment = $segment;
    }

    public function getNotification()
    {
        if (\request()->hasHeader('X-CALLBACK-TOKEN') && \request()->header('X-CALLBACK-TOKEN')===$this->receivedKey){
            $request = \request()->all();
            return json_decode(json_encode([
                'error' => false,
                'data' => $request
            ]));
        }
        return json_decode(json_encode([
            'error' => true,
            'data' => null,
            'code' => 'NO_CALLBACK_TOKEN',
            'message' => 'No Valid Callback'
        ]));
    }

    public function send()
    {
        $data = json_encode($this->data);
        $this->headers = Arr::add($this->headers, 'Content-Length', strlen($data));
        $headers = Arr::flatten(collect($this->headers)->map(function ($value, $key) {
            return [$key . ':' . $value];
        })->toArray());
        try {
            $url = $this->baseUrl;
            if (strlen($this->segment) > 0) {
                $url .= '/' . $this->segment;
            }
            if (count($this->params) > 0) {
                $url .= '?' . http_build_query($this->params);
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":");
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
