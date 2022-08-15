<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeCompanyFormRequest extends FormRequest
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
     * rules validation
     *
     * @return void
     */
    public function rules()
    {
        return [
            'header_bgcolor'=>'required|max:10',
            'body_bgcolor'=>'required|max:10',
            'button_primary_bgcolor'=>'required|max:10',
            'button_primary_textcolor'=>'required|max:10',
            'button_secondary_bgcolor'=>'required|max:10',
            'button_secondary_textcolor'=>'required|max:10',
            'status'=>'required|digits_between:0,1',
        ];
    }
}
