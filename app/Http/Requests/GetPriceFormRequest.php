<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPriceFormRequest extends FormRequest
{
    /**
     * authorize
     *
     * @return void
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
            'customer' => 'nullable',
            'schedule' => 'required|date_format:"m/d/Y"',
            'adult' => 'required',
            'children' => 'nullable',
            'infant' => 'nullable',
            'product' => 'required|numeric|exists:tbl_product,id_product',
            'extra.*' => 'nullable|numeric|exists:tbl_extra,id_extra',
            'extra_qty.*' => 'nullable|numeric',
            'voucher_code' => 'nullable|max:100|exists:tbl_voucher,voucher_code',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function messages(){
        return [
            'schedule.required' => 'Schedule is required',
            'schedule.date_format' => 'Schedule format is not valid',
            'adult.required' => 'Adult is required',
            'product.required' => 'Product is required',
            'product.numeric' => 'Product is not valid',
            'product.exists' => 'Product is not found',
            'extra.required' => 'Extra is required',
            'extra.numeric' => 'Extra Item is not valid',
            'extra.exists' => 'Extra Item is not found',
            'voucher_code.max' => 'Voucher code max 100 char',
            'voucher_code.exists' => 'Voucher code not valid',
        ];
    }
}
