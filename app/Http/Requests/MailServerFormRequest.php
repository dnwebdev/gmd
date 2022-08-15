<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailServerFormRequest extends FormRequest
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
            'smtp_host'=>'required|max:500',
            'smtp_port'=>'required|integer|digits:3',
            'username'=>'required|max:100',
            'password'=>'required|max:100',
            'status'=>'required|digits_between:0,1',
        ];
    }
}
