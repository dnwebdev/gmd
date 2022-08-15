<?php

namespace App\Http\Controllers\Company\Ads;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdsSelectCtrl extends Controller
{
    
    /**
     * function get select city
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function getSelectCity(Request $request){
        // $id_country = $request->input('country',102);
        // $result = City::whereHas('state',function($state) use($id_country){
        //     $state->where('id_country',$id_country);
        // })->where('city_name','like','%'.$q.'%')->orderBy('city_name', 'asc')->get();
        $q = $request->input('keyword');
        $country = $request->query('id_country');
        $result = City::with('state')
            ->when($q, function ($query, $q) {
                return $query->where('city_name','like','%'.$q.'%');
            })
            ->when($country, function ($query, $country) {
                return $query->whereHas('state', function ($query) use ($country) {
                    return $query->where('id_country', $country);
                });
            })
            ->orderBy('city_name', 'asc')->get();

        return response()->json([
            'incomplete_results' => false,
            'items' => $result,
            'total_count' => count($result)
        ]);

    }
}
