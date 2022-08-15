<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MoneyValidation;
use App\Models\Ads;

class GoogleAdsRequest extends FormRequest
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
            'url'               => 'required|url|max:150',
            'business_category' => 'required|array',
            'country'           => 'required',
            'city'              => 'required',
            'language'          => ['required', 'array', Rule::in(array_keys(Ads::$languages))],
            'min_budget'        => 'required|numeric|min:10000',
            'start_date'        => 'required|date|after_or_equal:today',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'title1'            => 'required|max:30',
            'title2'            => 'required|max:30',
            'description'       => 'required|max:90',
            'phone_number'      => 'required|digits_between:6,20',
            'payment_method'    => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return collect(trans('premium.google.form'))->filter(function ($value) {
            return isset($value['label']);
        })->mapWithKeys(function ($value, $key) {
            return [$key => strtolower($value['label'])];
        })->toArray();
    }
}
