<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileFormRequest extends FormRequest
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
            'company_name'=>'required|max:100',
            'domain_name'=>'nullable|regex:"^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\.)+[A-Za-z]{2,6}$"|max:100',
            'email_company'=>'nullable|email|max:100',
            'phone_company'=>'nullable|max:15',
            'address_company'=>'nullable',
            'city_company'=>'nullable|integer|exists:tbl_city,id_city',
            'about'=>'nullable',
            'twitter_company'=>'nullable|max:100',
            'facebook_company'=>'nullable|max:100',
            'color_company'=>'nullable',
            'font_color_company'=>'nullable',
            'short_description'=>'nullable|max:150',
            'quote'=>'nullable|max:30',
            'password'=>'nullable|min:6|confirmed',
            'old_password'=>'required_with:password',
            'logo'  => 'nullable|image',
            'banner' => 'nullable|image'
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
            // 'company_name.required' => 'Company Name is required',
            // 'company_name.max' => 'Company Name Maximum 100 Char',

            // 'domain_name.max'  => 'Domain Name Maximum 100 Char',
            // 'domain_name.url'  => 'Invalid Domain Name Format',

            // 'email_company.email'  => 'Invalid Email Format',
            // 'email_company.max'  => 'Email maximum 100 Char',

            // 'phone_company.numeric'  => 'Invalid Phone Format',
            // 'phone_company.max'  => 'Phone maximum 20 Char',
            // 'address_company.max'  => 'Address Maximum 300 Char',
            'city_company.integer'  => 'Invalid City Format',
            'city_company.exists'  => 'Invalid City',
            //'short_description.max' => 'Maximum 150 Char',
            'quote.max' => 'Maximum 30 Char'

        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'company_name'      => strtolower(trans('setting_provider.operator_name')),
            'domain_name'       => strtolower(trans('setting_provider.website_address')),
            'email_company'     => strtolower(trans('setting_provider.business_email')),
            'phone_company'     => strtolower(trans('setting_provider.whatsaap_enable_phone_number')),
            'address_company'   => strtolower(trans('setting_provider.address_company')),
            'short_description' => strtolower(trans('setting_provider.short_description'))
        ];
    }
}
