<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentRegistrationForm extends FormRequest
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
            'first_name'=>'required|alpha|max:100',
            'last_name'=>'nullable|alpha|max:100',
            'email'=>'required|email|max:100|unique:tbl_user_agent,email',
            'phone'=>'required|numeric|digits_between:5,20|unique:tbl_user_agent,phone',
            'password'=>'required|confirmed|max:100',
            // 'repassword'=>'required|max:100',
            'company_name'=>'required|max:100',
            'domain'=>'nullable|max:500|regex:"^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\.)+[A-Za-z]{2,6}$"|unique:tbl_company,domain',
            'domain_memoria'=>'nullable|max:500|regex:"^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\.)+[A-Za-z]{2,6}$"|unique:tbl_company,domain_memoria',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function messages(){
        return [
            'first_name.required' => 'First Name is required',
            'first_name.alpha' => 'Invalid First Name format',
            'first_name.max' => 'First Name maximum 100 char',
            'last_name.required' => 'Invaid Last Name is format',
            'last_name.alpha' => 'Invalid Last Name format',
            'last_name.max' => 'Last Name maximum 100 char',
            'email.required' => 'E-Mail is required',
            'email.email' => 'Invalid E-Mail format',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'repassword.required' => 'Retype Password is required',
            'company_name.required' => 'Company Name is required',
            'domain.max' => 'Domain Name maximum 500 char',
            'domain.regex' => 'Invalid Domain Name format (www.yourdomain.com)',
            'domain_memoria.max' => 'Gomodo Domain maximum 500 char',
            'domain_memoria.regex' => 'Invalid Gomodo Domain format (yourdomain.'.env('APP_URL').')',
        ];
    }
}
