<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpFormRequest extends FormRequest
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
        $rules = [
            'title'=>'required',
            'message' => 'required',
            'screenshot'=>'nullable|image',
        ];

        return $rules;
    }

    /**
     * Message validation function
     *
     * @return void
     */
    /*public function message(){
        return [
            'screenshot.required' => 'Screenshot is required',
            'screenshot.image' => 'Image is not valid',
            'title.required' => 'Title is required',
            'message.required' => 'Message is required',
        ];
    }*/

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title'                 => strtolower(trans('help_provider.title')),
            'message'               => strtolower(trans('help_provider.message')),
            'screenshot'            => strtolower(trans('help_provider.screenshot'))
        ];
    }

}
