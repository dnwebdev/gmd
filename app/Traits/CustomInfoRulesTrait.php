<?php


namespace App\Traits;

use App\Models\CustomSchema;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

trait CustomInfoRulesTrait
{
    private function generateCustomRules($unique_id)
    {
        $custom_schemas = $this->schema($unique_id);

        $rules = $custom_schemas->mapWithKeys(function ($value) {
                $rule[] = 'required';

                switch ($value->type_custom) {
                    case 'number':
                        $rule[]     = 'numeric';
                        break;
                    case 'document':
                        $rule[]     = 'file';
                        $rule[]     = 'mimes:pdf,txt';
                        $rule[]     = 'max:10240';
                        break;
                    case 'photo':
                        $rule[]     = 'file';
                        $rule[]     = 'image';
                        $rule[]     = 'max:2048';
                        break;
                    case 'dropdown':
                    case 'choices':
                        $rule[]     = Rule::in($value->value);
                        break;
                    case 'checkbox':
                        $rule[]     = 'array';
                        break;
                    case 'textarea':
                        $rule[]     = 'max:300';
                        break;
                    case 'country':
                        $rule[]     = 'exists:tbl_country,id_country';
                        break;
                    case 'state':
                        $rule[]     = 'exists:tbl_state,id_state';
                        break;
                    case 'city':
                        $rule[]     = 'exists:tbl_city,id_city';
                        break;

                }

                return [
                    'custom.'.($value->fill_type == 'customer' ? '0' : '*').'.'.$value->id => $rule
                ];
            })->toArray();

        $attributes = $custom_schemas->mapWithKeys(function ($value) {
            return [
               'custom.'.($value->fill_type == 'customer' ? '0' : '*').'.'.$value->id => $value->label_name
            ];
        })->toArray();

        return [
            'rules'         => $rules,
            'attributes'    => $attributes
        ];
    }

    private function schema($unique_id) {
        return CustomSchema::whereHas('product', function ($query) use ($unique_id) {
            return $query->where('unique_code', $unique_id);
        })->get();
    }

    public function customRuleRefill(Request $request, $unique_id) {
        $custom_schemas = $this->schema($unique_id);
        $custom = [];

        foreach ($custom_schemas as $schema) {
            for ($x = 0; $x < ($schema->fill_type == 'customer' ? 1 : $request->input('pax')); $x++) {
                $custom[$x][$schema->id] = null;
            } 
        }

        $request->merge([
            'custom' => array_replace_recursive($custom, $request->input('custom', []))
        ]);
    }
}
