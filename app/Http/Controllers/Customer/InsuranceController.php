<?php

namespace App\Http\Controllers\Customer;

use App\Models\Insurance;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    public function getDataInsurance(Request $request)
    {
        $where = [
            'id' => $request->get('id')
        ];
        $whereProduct = [
            'unique_code' => $request->get('sku')
        ];
        $product = Product::where($whereProduct)->first();
        $insurance = Insurance::where($where)->first();
        if (!$insurance || !$product->insurances()->where('id', $insurance->id)->first()) {
            return apiResponse(404);
        }
        if ($product->duration_type == '0') {
            $duration = ceil($product->duration / 24);
        } else {
            $duration = $product->duration;
        }
        $totalInsurancePrice = 0;
        if ($insurance->active_pricings->count() > 0):
            $insurancePrice = $insurance->active_pricings()->where('day', '>=', $duration)->first()->price;
            if (!$insurancePrice) {
                $insurancePrice = $insurancePrice->active_pricings()->orderBy('day', 'desc')->first()->price;
            }
            $totalInsurancePrice = (int)$request->get('pax') * $insurancePrice;
        endif;
        return apiResponse(200, 'OK', [
            'total' => $totalInsurancePrice,
            'label'=>app()->getLocale()=='en'?$insurance->insurance_name.' Insurance':'Pembayaran Asuransi '.$insurance->insurance_name,
            'total_text'=>'IDR '.number_format($totalInsurancePrice,0)
        ]);
    }
}
