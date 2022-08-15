<?php

namespace App\Http\Requests\Api\Ota;

use Illuminate\Foundation\Http\FormRequest;

class ProductListRequest extends FormRequest
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
            'search' => 'string',
            'ota' => 'exists:otas,ota_slug',
            'provider' => 'string',
            'limit' => 'integer|min:1',
            'page' => 'integer|min:1',
            'status' => 'boolean'
        ];
    }
}