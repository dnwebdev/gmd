<?php


namespace Kayiz;


class SendImageAsync extends BaseApi
{
    protected $method = "POST";
    protected $segment = "async_send_image_url";
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareContent()
    {
        $data = [
            'phone_no'=>$this->phone,
            'message'=>$this->message,
            'url'=>$this->media_url
        ];
        $errors = [];
        foreach (collect($data)->except('message') as $item=>$value):
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
