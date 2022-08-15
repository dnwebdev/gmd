<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'first_name' => 'required|max:100',
            'last_name' => 'nullable|max:100',
            'email' => 'required|email|unique:tbl_user_agent,email|max:100',
            'password' => 'required|min:6|max:100',
            'business_category' => 'required|array|min:1',
            'business_category.*' => 'required|exists:tbl_business_category,id',
            'company_name' => 'required|max:100',
            'domain' => 'required|unique:tbl_company,domain_memoria|max:100',
            'agreement' => 'required',
            'ownership_status' => 'required|in:personal,corporate',
            'phone'=>'required',
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        if (isset($data['domain']) && trim($data['domain'])!==''){
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
