<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VoucherFormEditRequest extends FormRequest
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
            'voucher_code'=>[
                'required',
                Rule::unique('tbl_voucher')->where(function($query){
                    $company = $this->request->get('id_company');
                    $id_voucher = $this->request->get('id_voucher');
                    $query->where('id_company', $company)->where('id_voucher', '!=', $id_voucher);
                }),
                'max:15'
            ],
            'voucher_type'=>'required|digits_between:0,1',
            'customer'=>'nullable|required_if:voucher_type,0|integer',
            'product_type'=>'nullable|integer|exists:tbl_product_type,id_tipe_product',
            'product_category'=>'nullable|integer|exists:tbl_product_category,id_category',
            'product'=>'nullable|integer|exists:tbl_product,id_product',
            'voucher_description'=>'required|max:25',
            'minimum_amount'=>'required|numeric',
            'max_use'=>'required|integer',
            'currency'=>'size:3',
            'voucher_amount'=>'required|numeric|min:1'.($this->request->get('voucher_amount_type') == '1' ? '|max:100' : ''),
            // 'valid_start_date'=>'required|date_format:"j F, Y"',
            // 'valid_end_date'=>'required|date_format:"j F, Y"',
            // 'start_date'=>'required|date_format:"j F, Y"',
            // 'end_date'=>'required|date_format:"j F, Y"',
//            'valid_start_date'=>'required|date_format:"m/d/Y"',
//            'valid_end_date'=>'required|date_format:"m/d/Y"',
//            'start_date'=>'required|date_format:"m/d/Y"',
//            'end_date'=>'required|date_format:"m/d/Y"',
            'status'=>'required|digits_between:0,1',
            'min_people' => 'required|numeric|min:1',
            'max_people' => 'nullable|numeric|min:'.$this->request->get('min_people')
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
            'voucher_code.required' => \trans('voucher_provider.validate.voucher_code'),
            'voucher_code.unique' => \trans('voucher_provider.validate.voucher_code_unique'),
            'voucher_code.max' => 'Voucher Code Maximum 10 Char',
            'voucher_type.required'  => 'Voucher type is required',
            'voucher_type.digits_between'  => 'Invalid Voucher type Format',
            'customer.integer'  => 'Invalid Customer',
            'customer.required_if' => 'Customer required when Voucher type is Individual one time',
            'product_type.integer'  => 'Invalid Product Type',
            'product_category.integer'  => 'Invalid Product Category',
            'product.integer'  => 'Invalid Product',
            'voucher_description.required'  => \trans('voucher_provider.validate.voucher_description'),
            'minimum_amount.required'  => \trans('voucher_provider.validate.minimum_amount'),
            'max_use.integer'  => 'Invalid Max Use Format',
            'currency.size'  => 'Invalid Currency',
            'voucher_amount.numeric'  => \trans('voucher_provider.validate.voucher_amount'),
            'voucher_amount.min'  => \trans('voucher_provider.validate.voucher_amount_min'),
//            'valid_start_date.required'  => 'Valid Start Date is required',
//            'valid_end_date.required'  => 'Valid End Date is required',
//            'valid_start_date.date_format'  => 'Invalid Valid Start Date Format',
//            'valid_end_date.date'  => 'Invalid Valid End Date Format',
//            'start_date.required'  => 'Start Schedule Date is required',
//            'end_date.required'  => 'End Date Schedule is required',
//            'start_date.date_format'  => 'Invalid Start Schedule Date Format',
//            'end_date.date_format'  => 'Invalid End Date Schedule Format',
            'status.required'  => 'Status is required',
            'status.digits_between'  => 'Invalid Status Format',
            
        ];
    }


}
