<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSchedule extends FormRequest
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
            'product' => 'required|integer|exists:tbl_product,id_product',
            'schedule' => 'required|date_format:"m/d/Y"',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function messages(){
        return [
            'product.required' => 'Product is required',
            'product.integer' => 'Product is not valid',
            'product.exists' => 'Product is not found',
            'schedule.required' => 'Schedule is required',
            'schedule.date_format' => 'schedule is not appropriate',
        ];
    }
}
