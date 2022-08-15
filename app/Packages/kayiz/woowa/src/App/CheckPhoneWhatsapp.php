<?php


namespace Kayiz;


class CheckPhoneWhatsapp extends BaseApi
{
    protected $method = "POST";
    protected $segment = "check_number";
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareContent()
    {
        $data = [
            'phone_no'=>$this->phone,
        ];
        $errors = [];
        foreach ($data as $item=>$value):
            if (empty($value)):
                $errors[$item] = trans('validation.required',['attribute'=>$item]);
            endif;
        endforeach;
        $this->setErrors($errors)->setContent($data);
        return $this;
    }

    public function getResponse()
    {
        if ($this->response['ok']):
            if (strtolower($this->response['response']) == 'exists'):
                return ['status' => true];
            else:
                return ['status' => false,'message'=>$this->response['response']];
            endif;
        else:
            return ['status' => false, 'message' => $this->message, 'errors' => $this->errors];
        endif;
    }
}
