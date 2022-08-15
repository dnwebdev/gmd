<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxFormRequest extends FormRequest
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
            'tax_name'=>'required|max:30',
            'tax_amount'=>'required|numeric',
            'tax_amount_type'=>'required|integer|digits_between:0,1',
            'status'=>'required|digits_between:0,1',
        ];
    }


    /**
     * Message validation function
     *
     * @return void
     */
    public function messages()
    {
        return [
            'tax_name.required' => 'Tax Name is required',
            'tax_name.max' => 'Tax Name Maximum 30 Char',
            'tax_amount.required'  => 'Tax Amount is required',
            'tax_amount_type.required'  => 'Tax Amount Type is required',
            'tax_amount_type.integer'  => 'Invalid Tax Amount Type Format',
            'tax_amount_type.digits_between'  => 'Invalid Tax Amount Type Format',
            'status.required'  => 'Status is required',
            'status.digits_between'  => 'Invalid Status Format',
            
        ];
    }
}
