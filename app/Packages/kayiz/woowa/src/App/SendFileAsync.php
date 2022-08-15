<?php

namespace Kayiz;

class SendFileAsync extends BaseApi {
    protected $method = "POST";
    protected $segment = "async_send_file_url";
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareContent()
    {
        $data = [
            'phone_no'=>$this->phone,
            'url'=>$this->media_url
        ];
        $errors = [];
        foreach ($data as $item => $value):
            if (empty($value)):
                $errors[$item] = 'This field is required';
            endif;
        endforeach;
        $this->setErrors($errors)->setContent($data);
        return $this;
    }

    public function getResponse()
    {
        if (!empty($this->response['response'])):
            return ['status' => true];
        else:
            return ['status' => false, 'message' => $this->response['response']];
        endif;
    }
}
