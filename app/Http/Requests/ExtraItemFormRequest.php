<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtraItemFormRequest extends FormRequest
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
            'extra_name' => 'required|max:100',
            'currency' => 'required|max:3',
            'amount' => 'required|numeric',
            'extra_price_type' => 'required|digits_between:0,1',
            'description' => 'required|max:500',
            'image' => 'nullable|image',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function message(){
        return [
            'extra_name.required' => 'Extra Name is required',
            'extra_name.max' => 'Extra Name is 100 max char',
            'currency.required' => 'Currency is required',
            'currency.max' => 'Currency is not valid',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount is not valid',
            'extra_price_type.required' => 'Price Type is required',
            'extra_price_type.digits_between' => 'Price Type is not valid',
            'description.required' => 'Description is required',
            'description.max' => 'Description is 500 char max',
            'image.image' => 'Image is not valid',
        ];
    }
}
