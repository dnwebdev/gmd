<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{


    /**
     * function search countries
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function countries(Request $request){
    	$keyword = $request->get('query');
    	$where = [];

    	if(!empty($keyword)){
    		array_push($where,['country_name','LIKE','%'.$keyword.'%']);
    	}

    	$country = \App\Models\Country::where($where)->skip(0)->take(10)->get();
    	$d_data = [];
    	foreach($country as $row){
    		array_push($d_data,['value'=>$row->country_name,'data'=>$row->id_country]);
    	}
    	return response()->json([
                                'suggestions' => $d_data

                            ]);


    }

    /**
     * function search cities
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function cities(Request $request){
    	$keyword = $request->get('query');
    	$country = $request->get('country');


    	$where = [];
    	if(!empty($keyword)){
    		array_push($where,['city_name','LIKE','%'.$keyword.'%']);
    	}

        //\DB::enableQueryLog();


        $city = \App\Models\City::where($where)->whereHas('State', function($q) use ($country){
            $q->where('id_country', $country);
        })->skip(0)->take(10)->get();


    	$d_data = [];
    	foreach($city as $row){
    		array_push($d_data,['value'=>$row->city_name,'data'=>$row->id_city]);
    	}
    	return response()->json([
                                'suggestions' => $d_data
                            ]);
    }

    /**
     * function search state
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function state(Request $request){
      $keyword = $request->get('query');
      $country = $request->get('country');


      $where = [];
      if(!empty($keyword)){
        array_push($where,['state_name','LIKE','%'.$keyword.'%']);
      }

        //\DB::enableQueryLog();


        $state = \App\Models\State::where($where)->whereHas('country', function($q) use ($country){
            $q->where('id_country', $country);
        })->skip(0)->take(10)->get();


      $d_data = [];
      foreach($state as $row){
        array_push($d_data,['value'=>$row->city_name,'data'=>$row->id_city]);
      }
      return response()->json([
                                'suggestions' => $d_data
                            ]);
    }


}
