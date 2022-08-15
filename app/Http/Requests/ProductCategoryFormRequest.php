<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryFormRequest extends FormRequest
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
            'category_name'=>'required|max:100',
            'product_type'=>'required|numeric|exists:tbl_product_type,id_tipe_product',
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
            'category_name.required' => 'Category Name is required',
            'category_name.max' => 'Category Name Maximum 100 Char',
            'product_type.required'  => 'Product Type is required',
            'product_type.numeric'  => 'Invalid Product Type Format',
            'product_type.exists'  => 'Invalid Product Type',
            'status.required'  => 'Status is required',
            'status.digits_between'  => 'Invalid Status Format',
            
        ];
    }


}
