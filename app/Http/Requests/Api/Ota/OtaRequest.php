<?php

namespace App\Http\Requests\Api\Ota;

use Illuminate\Foundation\Http\FormRequest;

class OtaRequest extends FormRequest
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
            'name' => 'required',
            'slug'=> 'required|unique:otas,ota_slug,'.$this->route('id'),
            'image'=> 'nullable|image',
            'status'=> 'boolean',
            'original_markup'=> 'required|numeric|min:0|max:50',
            'gomodo_markup'=> 'required|numeric|min:0|max:50',
        ];
    }
}