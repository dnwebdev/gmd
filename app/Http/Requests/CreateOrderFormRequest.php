<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderFormRequest extends FormRequest
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
            'customer'=>'nullable|numeric|exists:tbl_customer,id_customer',
            'email'=>'required|email|max:100',
            'first_name'=>'required|regex:/^[\pL\s\-]+$/u|max:100',
            'last_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:100',
            'phone' => 'required|numeric|digits_between:5,20',
            'country' => 'nullable|integer|exists:tbl_country,id_country',
            'city' => 'nullable|integer|exists:tbl_city,id_city',
            'address' => 'required|max:500',
            'internal_notes' => 'nullable|max:2000',
            'external_notes' => 'nullable|max:2000',
            'status' => 'required|numeric|digits_between:0,7',
            'void_reason' => 'required_if:status,99|max:300',
            /*'schedule' => 'required',
            'adult' => 'required',
            'children' => 'nullable',
            'infant' => 'nullable',
            'product' => 'required|numeric|exists:tbl_product,id_product',
            'extra.*' => 'nullable|numeric|exists:tbl_extra,id_extra',
            'extra_qty.*' => 'required_with:extra|numeric',*/
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function messages(){
        return [
            'email.required' => 'Email is required',
            'first_name.required' => 'First Name is required',
            'phone.required' => 'Phone is required',
            'country.required' => 'Country is required',
            'city.required' => \trans('product_provider.city_required'),
            'status.required' => 'Transaction status is required',
            'status.numeric' => 'Invalid transaction status format',
            'status.digits_between' => 'Invalid transaction status',
            'void_reason.required_if' => 'Void Reason is required',
            /*'schedule.required' => 'Schedule is required',
            'adult.required' => 'Adult is required',
            'product.required' => 'Product is required',
            'product.numeric' => 'Product is not valid',
            'product.exists' => 'Product is not found',
            'extra.required' => 'Extra is required',
            'extra.numeric' => 'Extra Item is not valid',
            'extra.exists' => 'Extra Item is not found',*/
        ];
    }
}
