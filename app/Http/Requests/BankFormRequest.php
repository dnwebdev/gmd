<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CompanyBank;
use Auth;

class BankFormRequest extends FormRequest
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
        $hasBank = CompanyBank::where('id_company', Auth::user()->id_company)->exists();

        return [
            'bank'=>'required',
            'bank_account_name'=>'required|max:50',
            'bank_account_number'=>'required|numeric|digits_between:5,25',
            'status'=>'numeric|digits_between:0,1',
            'bank_account_document'=> ($hasBank ? 'required' : 'nullable').'|image',
        ];
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function message(){
        return [
            // 'bank.required'=>'Bank is required',
            // 'bank.max'=>'Bank maximum 100 char',
            // 'bank_account_name.required'=>'Bank Account Name is required',
            // 'bank_account_name.max'=>'Bank Account Name maximum 100 char',
            // 'bank_account_number.required'=>'Bank Account Number is required',
            // 'bank_account_number.max'=>'Bank Account Number maximum 100 char',
            'status.required' => 'Bank Account status is required',
            'status.numeric' => 'Invalid Bank Account status format',
            'status.digits_between' => 'Invalid Bank Account status',
            // 'bank_account_document.required'=>'Bank Document is required',
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
            'bank'                  => strtolower(trans('bank_provider.select_bank')),
            'bank_account_name'     => strtolower(trans('bank_provider.account_name')),
            'bank_account_number'   => strtolower(trans('bank_provider.account_number')),
            'bank_account_document' => strtolower(trans('bank_provider.bank_document'))
        ];
    }
}
