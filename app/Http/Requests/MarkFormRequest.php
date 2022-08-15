<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkFormRequest extends FormRequest
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
            'mark'=>'required|max:100',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function message(){
        return [
            'mark.required'=>'Product Mark is required',
            'mark.max'=>'Product Mark maximum 100 char',
        ];
    }
}
