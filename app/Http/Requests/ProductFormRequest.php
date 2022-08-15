<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Validation\Rule;

class ProductFormRequest extends FormRequest
{

    /**
     * authorize
     *
     * @return void
     */
    public function authorize()
    {
        return true;
    }


    /**
     * rules validation
     *
     * @return void
     */
    public function rules()
    {
        $id_product =  $this->input('product');
        
        $maxi = $this->request->get('max_people');
        $mini = $this->request->get('min_people');

        return [
            'product_type'=>'required|numeric|exists:tbl_product_type,id_tipe_product',
            'country'=>'required|exists:tbl_country,id_country',
            'state'=>'required|exists:tbl_state,id_state',
            'city'=>'required|exists:tbl_city,id_city',
            'product_category'=>'nullable',
            'long_description'=>'required',
            'product_name'=>'required|max:100',
            'permalink'=>'nullable|max:500',
            'unique_code'=> 'nullable|max:100|unique:tbl_product,unique_code,'.$id_product.',id_product',
            // 'city'=>'nullable|integer|exists:tbl_city,id_city',
            // 'country'=>'nullable|integer|exists:tbl_country,id_country',
            'duration'=>'required|numeric|min:1',
            'duration_type'=>'nullable|digits_between:0,1',
            'brief_description'=>'required|max:100',
            'itinerary'=>'nullable',
            'important_notes'=>'nullable',
            'min_order'=>'required|numeric',
            'max_order'=>'nullable|numeric',
            'min_people'=>'required|numeric',
            'max_people'=>'required|numeric',
            // 'currency' => 'required|max:3',
            'currency' => 'max:3',
            // 'advertised_price' => 'numeric',
            'discount_name'=>'nullable',
            'discount_amount_type'=>'nullable|digits_between:0,1',
            'discount_amount'=>'nullable|numeric',
            'price_from.*'=> 'required|numeric|digits_between:0,5|min: '.$mini,
            'price_until.*'=>'required|numeric|max:'.$maxi,
            'mark'=>'nullable|numeric',
            'fee_name'=>'nullable|max:100',
            'fee_amount'=>'nullable|numeric',
            'minimum_notice'=>'required|numeric|min:0',
            'booking_confirmation'=>'nullable|digits_between:0,1',
            'addon1'=>'nullable',
            'addon2'=>'nullable',
            'addon3'=>'nullable',
            'addon4'=>'nullable',
            'addon5'=>'nullable',
            'addon6'=>'nullable',
            'addon7'=>'nullable',
            'addon8'=>'nullable',
            'availability'=>'nullable',
            
            // 'status'=>'required|digits_between:0,1',
            // 'publish'=>'required|digits_between:0,1',
            'status'=>'numeric',
            'publish'=>'numeric',
            // 'price_type.*'=> 'required|digits_between:0,3',
            'unit_name_id'=>'required|array',
            'price.*'=>'required|numeric',
            'people.*'=>'nullable|numeric',
            'tax.*'=>'nullable|integer|exists:tbl_tax,id_tax',
            //'start_date.*'=> 'required_if:availability,0|date_format:"j F, Y"',
            //'end_date.*'=>'required_if:availability,0|date_format:"j F, Y"',
            //'start_date.*'=> 'required_if:availability,0|date_format:"m/d/Y"',
            //'end_date.*'=> 'required_if:availability,0|date_format:"m/d/Y"',
            'start_date'=>'required',
            'start_date.*'  => 'required|date_format:"m/d/Y"',
            'end_date.*'    => $this->request->get('availability') == 1  ? 'required|date_format:"m/d/Y"' : 'nullable',
            'start_time.*' => 'nullable',
            'end_time.*' => 'nullable',
            'sun.*'=> 'required_if:availability,0|digits_between:0,1',
            'mon.*'=> 'required_if:availability,0|digits_between:0,1',
            'tue.*'=>'required_if:availability,0|digits_between:0,1',
            'wed.*'=>'required_if:availability,0|digits_between:0,1',
            'thu.*'=>'required_if:availability,0|digits_between:0,1',
            'fri.*'=>'required_if:availability,0|digits_between:0,1',
            'sat.*'=>'required_if:availability,0|digits_between:0,1',
            'images.*'=>'nullable|image',

            'custom_type.*'         => [
                'nullable',
                Rule::in(array_keys(\App\Models\CustomSchema::$types))
            ],
            'custom_fill_type.*'    => [
                'nullable',
                Rule::in(array_keys(\App\Models\CustomSchema::$fill_types))
            ],
            'custom_label.*'        => 'nullable',
            'custom_description.*'  => 'nullable|max:300',
            'custom_values.*.*'     => 'required_if:custom_type.*,checkbox,choices,dropdown'
        ];


    }


    /**
     * Message validation function
     *
     * @return void
     */
    public function messages()
    {
        $maxi = $this->request->get('max_people');
        $mini = $this->request->get('min_people');

        $return = [
            'product_name.max' => \trans('product_provider.product_name_required'),
            'category_name.required' => \trans('product_provider.country_required'),
            'unit_name_id.required' => 'Category is required',
            'country.required' => \trans('product_provider.country_required'),
            'state.required' => \trans('product_provider.state_required'),
            'city.required' => \trans('product_provider.city_required'),
            'category_name.max' => \trans('product_provider.category_name_max'),
            'product_type.required'  => \trans('product_provider.product_type_required'),
            'product_type.numeric'  => \trans('product_provider.product_type_numeric'),
            'product_type.exists'  => \trans('product_provider.product_type_exists'),
            'status.required'  => \trans('product_provider.status_required'),
            'status.digits_between'  => \trans('product_provider.status_digits_between'),
            'publish.required'  => \trans('product_provider.publish_required'),
            'publish.digits_between'  => \trans('product_provider.publish_digits_required'),
            'price.*.required'=> \trans('product_provider.price_required'),
            'price_from.*.required'=> \trans('product_provider.price_from_required'),
            'price_from.*.min'=> \trans('product_provider.price_from_min').$mini,
            'price_until.*.required'=> \trans('product_provider.price_until_required'),
            'price_until.*.max'=> \trans('product_provider.price_until_max').$maxi,
            'important_notes.max'=> \trans('product_provider.important_notes_max'),
            'start_time.*.date_format' => \trans('product_provider.start_time_date_format'),
            'end_time.*.date_format' => \trans('product_provider.end_time_date_format'),
//            'start_date[].required'=>__('validation.required','Start Date')
        ];

        if (!empty($this->request->get('custom_values'))) {
            foreach ($this->request->get('custom_values') as $key => $value) {
                $name = $this->request->get('custom_label')[$key];
                $return['custom_values.'.$key.'.*'] = \trans('product_provider.custom_value_validation', ['label' => $name]);
            }
        }

        return $return;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $return = [
            'custom_values.*.*' => 'value',
            'custom_type.*'     => strtolower(\trans('product_provider.custom_type')),
            'start_date.*'      => strtolower(\trans('product_provider.start_date')),
            'start_date'      => strtolower(\trans('product_provider.start_date')),
            'end_date.*'        => strtolower(\trans('product_provider.end_date')),
            'brief_description' => strtolower(trans('product_provider.brief_description')),
            'long_description'  => strtolower(trans('product_provider.about_this_product')),
            'images.*'          => trans('product_provider.images'),
        ];

        if (!empty($this->request->get('custom_description'))) {
            foreach ($this->request->get('custom_description') as $key => $value) {
                $name = $this->request->get('custom_label')[$key];
                $return['custom_description.'.$key] = strtolower(trans('product_provider.brief_description')) .' '.$name;
            }
        }

        return $return;
    }
}
