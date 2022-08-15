<?php


namespace Kayiz;


abstract class BaseApi
{
    protected $baseUrl = 'http://116.203.92.59/api/';
    protected $segment = 'send_message';
    protected $phone;
    protected $media_url;
    protected $method = 'POST';
    protected $message;
    protected $content = [];
    protected $errors = [];
    protected $key;
    protected $response;

    public function __construct()
    {
        $this->key = config('woowa.key');
        $this->baseUrl = config('woowa.base_url');
    }

    public function send()
    {
        if (count($this->errors) > 0):
            $this->message = 'Invalid Data';
            $this->response = [
                'ok' => false,
                'message' => 'Invalid Data',
                'errors' => $this->errors
            ];
            return $this;
        endif;
        $data_string = json_encode($this->content);

        $ch = curl_init($this->baseUrl . $this->segment);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            $this->response = [
                'ok' => false,
                'message' => $error_msg,
                'errors' => []
            ];
            return $this;
        }
         $this->response = [
            'ok' => true,
            'message' => 'OK',
            'response' => $res
        ];;
        return $this;
    }

    protected function setContent($data)
    {
        $content['key'] = $this->key;
        foreach ($data as $item => $value):
            $content[$item] = $value;
        endforeach;
        $this->content = $content;
        return $this;
    }

    protected function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function setMethod($method)
    {
        in_array(strtoupper($method), ['POST', 'PUT', 'DELETE', 'GET', 'PATCH']) ?
            $this->method = strtoupper($method) :
            $this->method = 'POST';
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setMediaUrl($url)
    {
        $this->media_url = $url;
        return $this;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

}
