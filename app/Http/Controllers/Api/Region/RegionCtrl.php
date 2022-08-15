<?php

namespace App\Http\Controllers\Api\Region;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionCtrl extends Controller
{
    /**
     * Api to get All Countries
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries()
    {
        $countries = Country::orderBy('country_name', 'asc')->get();
        return response()->json($countries);
    }

    /**
     * Api to get all States from Country ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStateFromCountry(Request $request)
    {
        $rule = [
            'id_country' => 'required|exists:tbl_country,id_country'
        ];

        $this->validate($request, $rule);
        $country = Country::find($request->id_country);
        $states = $country->state()->orderBy('state_name', 'asc')->get();
        return response()->json($states);

    }

    /**
     * Api get All Cities from State ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityFromState(Request $request)
    {
        $rule = [
            'id_state' . (is_array($request->input('id_state')) ? '.*' : '') => 'required|exists:tbl_state,id_state'
        ];

        $this->validate($request, $rule);
        // $state = State::find($request->id_state);
        $cities = City::whereIn('id_state', (array)$request->input('id_state'))
            ->orderBy('city_name', 'asc')
            ->get();
        return response()->json($cities);
    }

    /**
     * get Regional Data ex country, state, and city by city ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataRegionalFromCityId(Request $request)
    {
        $rule = [
            'id_city' => 'required|exists:tbl_city,id_city'
        ];

        $this->validate($request, $rule);
        $city = City::where('id_city', $request->id_city)->first();
        $data['selectedCountry'] = $city->state->country->id_country;
        $data['selectedState'] = $city->state->id_state;
        $data['selectedCity'] = $city->id_city;
        $data['allCountry'] = Country::orderBy('country_name', 'asc')->get();
        $data['allState'] = $city->state->country->state()->orderBy('state_name', 'asc')->get();
        $data['allCity'] = $city->state->city()->orderBy('city_name', 'asc')->get();
        return response()->json($data);
    }

    public function searchCity(Request $request)
    {
        $rule = [
            'q' => 'required|min:3'
        ];
        $this->validate($request, $rule);

        $cities = City::where(function ($c) use ($request) {
            $c->where('city_name', 'like', '%' . $request->get('q') . '%')
                ->orWhere('city_name_en', 'like', '%' . $request->get('q') . '%');
        });
        if ($request->filled('state') && $request->get('state') == '1'):
            $cities = $cities->orWhereHas('state', function ($state) use ($request) {
                $state->where('state_name', 'like', '%' . $request->get('q') . '%')
                    ->orWhere('state_name_en', 'like', '%' . $request->get('q') . '%');
            });
        endif;
        if ($request->filled('id_country')) {
            $cities = $cities->whereHas('state', function ($state) use ($request){
                $state->where('id_country',$request->get('id_country'));
            });
        }
        $cities = $cities->paginate(5);
        $result = $cities->map(function ($city) {
            return [
                'id_city' => $city->id_city,
                'city_name' => app()->getLocale() == 'id' ? $city->city_name . ' - ' . $city->state->state_name : $city->city_name_en . ' - ' . $city->state->state_name_en
            ];
        });
        $result = [
            'hasNext' => $cities->hasMorePages(),
            'data' => $result
        ];
        return response()->json($result);
    }
}
