<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStepOne extends FormRequest
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
            'phone' => 'required|min:6|max:20',
            'email' => 'required|email|unique:tbl_user_agent,email|max:100',
            'password' => 'required|min:6|max:100'
        ];
    }
}
