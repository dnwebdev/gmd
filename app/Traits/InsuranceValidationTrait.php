<?php
namespace App\Traits;
trait InsuranceValidationTrait
{
    function validationInsurance(\Illuminate\Http\Request $request, $insuranceId)
    {
        $result = [
            'rule'=>[],
            'attributes'=>[]
        ];
        $insurance = \App\Models\Insurance::find($insuranceId);
        if (!$insurance) {
            return $result;
        }
        $pax = (int)$request->get('pax');
        $rules = [];
        $attributes = [];
        foreach ($insurance->customer_forms as $customer_form):
            $tempRule = [];
            switch ($customer_form->type):
                case 'phone':
                    $tempRule[] = 'phone:ID,auto';
                    break;
                case 'date':
                    $tempRule[] = 'date_format:Y-m-d';
                    break;
                case 'ktp':
                    $tempRule[] = 'numeric';
                    $tempRule[]= 'digits_between:14,16';
                    break;

                default:
                    $tempRule[] = 'required';

                    break;

            endswitch;
            $rules['insurances.'.$insuranceId.'.customer.'.$customer_form->name] = $tempRule;
            $attributes['insurances.'.$insuranceId.'.customer.'.$customer_form->name] = strtolower($customer_form->label);
        endforeach;
        for ($i = 1; $i <= $pax; $i++):
            foreach ($insurance->participant_forms as $participant_form):
                $tempRule = [];
                switch ($participant_form->type):
                    case 'phone':
                        $tempRule[] = 'required';
                        $tempRule[] = 'numeric';
                        $tempRule[]= 'digits_between:6,15';
                        break;
                    case 'date':
                        $tempRule[] = 'required';
                        $tempRule[] = 'date_format:Y-m-d';
                        break;
                    case 'ktp':
                        $tempRule[] = 'required';
                        $tempRule[] = 'numeric';
                        $tempRule[]= 'digits_between:14,16';
                        break;

                    default:
                        $tempRule[] = 'required';

                        break;

                endswitch;
                $rules['insurances.'.$insuranceId.'.participants.'.$i.'.'.$participant_form->name] = $tempRule;
                $attributes['insurances.'.$insuranceId.'.participants.'.$i.'.'.$participant_form->name] =  strtolower($participant_form->label);
            endforeach;
        endfor;

        $result = [
            'rule'=>$rules,
            'attributes'=>$attributes
        ];
        return $result;


    }
}