<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsRequest extends FormRequest
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
        $min = 10000;
        return [
            'category_ads'      => 'required|array',
            'purpose'           => 'required',
            'title'             => 'required|max:40',
            'description'       => 'required|max:125',
            'call_button'       => 'required',
            'url'               => 'required|max:150|url', //:regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'document_ads'      => 'required|image|max:2048|mimes:jpeg,png,jpg,gif',
            'gender'            => 'required',
            'age'               => 'required',
            'city'              => 'required|array',
            'min_budget'        => 'required|numeric|min:'.$min,
            'voucher_code'      => 'nullable',
            'start_date'        => 'required|date|after_or_equal:today',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'payment_method'    => 'required',
            'term_conditions'   => 'required|boolean',
        ];
    }

    /**
     * function get format Min Budget
     *
     * @return void
     */
    protected function formatMinBudget()
    {
        $this->merge([
            'min_budget' =>  str_replace(',','', $this->request->get('min_budget')),
        ]);
    }

    /**
     * function get Validator Instance
     *
     * @return void
     */
    public function getValidatorInstance()
    {
        $this->formatMinBudget();

        return parent::getValidatorInstance();
    }
    

    /**
     * Message validation function
     *
     * @return void
     */
    public function messages(){
        $min = 10000;
        return[
            'url.url' => \trans('premium.facebook.validate.url'),
            'purpose.required' => \trans('premium.facebook.validate.purpose'),
            'title.required' => \trans('premium.facebook.validate.title'),
            'description.required' => \trans('premium.facebook.validate.description'),
            'call_button.required' => \trans('premium.facebook.validate.call_button'),
            'gender.required' => \trans('premium.facebook.validate.gender'),
            'age.required' => \trans('premium.facebook.validate.age'),
            'city.required' => \trans('premium.facebook.validate.city'),
            'min_budget.required' => \trans('premium.facebook.validate.min_budgetRequired'),
            'min_budget.min' => \trans('premium.facebook.validate.min_budget').' '. $min,
            'document_ads.required' => \trans('premium.facebook.validate.document'),
            'term_conditions.required' => \trans('premium.facebook.validate.term_conditions'),
            'payment_method.required' => \trans('premium.facebook.validate.payment_method'),
        ];
    }
}
