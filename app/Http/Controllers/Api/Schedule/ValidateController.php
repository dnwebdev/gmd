<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Http\Controllers\Controller;
use App\Traits\ValidationScheduleTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ValidateController extends Controller
{
    use ValidationScheduleTrait;
    public function validateSchedule(Request $request)
    {
        if (!$request->wantsJson()) {
            return response()->json(['message' => 'Not permitted'], 403);
        }
        $rule = [
            'sku' => [
                'required',
                Rule::exists('tbl_product', 'unique_code')->where('id_company',
                    auth('api')->user()->company->id_company)->where('status', 1)
            ],
            'schedule_date' => 'required|date_format:Y-m-d',
            'pax' => 'required|min:1'
        ];
        $this->validate($request, $rule);
        $validate = $this->scheduleValidation($request->get('sku'), $request->get('schedule_date'),
            auth('api')->user()->company->id_company, $request->get('pax'));
        return response()->json($validate['result'], $validate['status']);

    }
}