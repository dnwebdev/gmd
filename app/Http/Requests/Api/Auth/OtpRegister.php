<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'domain' => 'required|max:100|min:3|unique:tbl_company,domain_memoria',
            'agreement' => 'required',
            'phone'=> $this->request->get('use') == 1 ? 'required|min:8|max:20|unique:tbl_user_agent,phone' : 'nullable',
            'email' => $this->request->get('use') == 0 ? 'required|email|unique:tbl_user_agent,email|max:100' : 'nullable',
            'password' => $this->request->get('use') == 0 ? 'required|min:6|max:100' : 'nullable',
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        if (isset($data['domain']) && trim($data['domain'])!==''){
            if (strlen($data['domain']) < 3){
                return $this->all();
            }
            return array_merge(
                $this->all(),
                [
                    'domain' => preg_replace('/\s+/', '', strtolower($data['domain'])) . '.' . env('APP_URL')
                ]
            );
        }
        return $this->all();
    }
}
