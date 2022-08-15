<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivateOtp extends FormRequest
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
//            'phone'=>'required|exists:tbl_user_agent,phone',
            'otp' => [
                'required',
                'digits:4',
                Rule::exists('user_otps', 'otp')->where('type', 'register')
            ],
            'password'=>'required|min:6|confirmed'
        ];
    }
}
