<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerFormRequest extends FormRequest
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
            'link'=>'nullable|url',
            'description' => 'nullable',
            'status'=>'required|digits_between:0,1',
        ];
        
        if (!$this->has('id'))
        {
            $rules += ['image' => 'required|image'];
        }

        return $rules;
    }

    /**
     * Message validation function
     *
     * @return void
     */
    public function message(){
        return [
            'link.url' => 'Link is not valid',
            'image.required' => 'Image is required',
            'image.image' => 'Image is not valid',
            'status.required' => 'Status is required',
            'status.digits_between' => 'Status is not valid',
        ];
    }

}
