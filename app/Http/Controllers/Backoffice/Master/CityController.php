<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.master.city.index');
    }

    public function loadData(Request $request)
    {
        $model = City::where('id_state', $request->input('state'));
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->city_name_en . '"data-nameindo="' . $model->city_name . '"  data-id="' . $model->id_city . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                return $html;
            })
            ->make(true);
    }

    public function update(Request $request)
    {
        $rule = [
            'id_city' => 'required|exists:tbl_city,id_city',
            'city_name_en' => [
                'required',
                Rule::unique('tbl_city', 'city_name_en')->whereNot('id_city', $request->id_city)
            ],
            'city_name' => [
                'required',
                Rule::unique('tbl_city', 'city_name')->whereNot('id_city', $request->id_city)
            ],
        ];
        $this->validate($request, $rule);
        $lang = City::find($request->id_city);
        if ($lang) {
            $lang->update([
                'city_name_en' => $request->input('city_name_en'),
                'city_name' => $request->input('city_name'),
            ]);
            msg('City Updated!');
            return redirect()->back();
        }
        msg('City not found!', 2);
        return redirect()->back();
    }

    public function changeCountry(Request $request)
    {
        return apiResponse(200,'OK',State::whereIdCountry($request->input('country'))->get()->pluck('state_name','id_state'));
    }
}
