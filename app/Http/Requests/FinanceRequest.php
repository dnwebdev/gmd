<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceRequest extends FormRequest
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
        $company = auth('web')->user()->company;
        if ($company->ownership_status == 'personal'){
            $rules = [
                'ktp_couples' => 'nullable|image',
                'family_card' => 'required_if:use_kyc,0|image',
            ];
        }else{
            $rules = [
                'siup' => 'required_if:use_kyc,0|image',
                'founding_deed' => 'required_if:use_kyc,0|image',
                'change_certificate' => 'nullable|image',
                'sk_menteri' => 'required|image',
                'company_signatures' => 'required_if:use_kyc,0|image',
                'report_statement' => 'required|image',
            ];
        }
        return array_merge([
            'type_finance' => 'required',
            'time_finance' => 'required',
            'use_kyc' => 'nullable|digits_between:0,1',
            'ktp' => 'required_if:use_kyc,0|image',
            'npwp' => 'required_if:use_kyc,0|image',
//            'ktp' => $this->request->get('use_kyc') == 1  ? 'required|image' : 'nullable',
//            'npwp' => $this->request->get('use_kyc') == 1  ? 'required|image' : 'nullable',
            'document_bank' => 'required|image',
            'min_suku_bunga' => 'nullable',
            'max_suku_bunga' => 'nullable',
            'fee_provisi' => 'nullable',
            'fee_penalty_delay' => 'nullable',
            'fee_settled' => 'nullable',
            'fee_insurance' => 'nullable',
            'amount' => 'required|numeric|min:10000000|max:2000000000',
            'status' => 'nullable',
        ], $rules);
    }

    public function messages()
    {
        $min = number_format('10000000', 0);
        $max = number_format('2000000000', 0);

        return [
            'amount.min' => \trans('finance.validation.min_amount').' '. $min, 
            'amount.max' => \trans('finance.validation.max_amount').' '. $max,
            'ktp.required_if' => \trans('finance.validation.ktp'),
            'npwp.required_if' => \trans('finance.validation.npwp'),
            'siup.required_if' => \trans('finance.validation.siup'),
        ];
    }
}
