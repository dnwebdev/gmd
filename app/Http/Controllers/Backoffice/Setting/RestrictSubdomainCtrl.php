<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Models\RestrictSubDomain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestrictSubdomainCtrl extends Controller
{
    public function index()
    {
        return view('back-office.page.setting.restrict.index');
    }

    public function loadData()
    {
        $model = RestrictSubDomain::query();

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('subdomain', function ($model) {
                return $model->subdomain . '.' . env('APP_URL');
            })
            ->addColumn('action', function ($model) {
                $html = '<button  
                            data-id="' . $model->id . '" 
                            data-name="' . $model->subdomain . '"
                            class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">                     
                         <i class="fa flaticon-visible"></i>
                         </button>';
                $html .= ' <button  
                             data-id="' . $model->id . '" 
                             data-name="' . $model->subdomain . '"
                            class="btn btn-outline-danger btn-delete btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">                        
                         <i class="fa flaticon-delete"></i>
                         </button>';
                return $html;

            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'subdomain' => 'required|alpha_num|unique:blacklist_sub_domains,subdomain'
        ];
        $this->validate($request, $rules);
        RestrictSubDomain::create([
            'subdomain' => $request->subdomain
        ]);
        return apiResponse(200, $request->subdomain . '.' . env('APP_URL') . ' restricted');
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|exists:blacklist_sub_domains,id',
            'subdomain' => 'required|alpha_num|unique:blacklist_sub_domains,subdomain,' . $request->input('id'),
        ];
        $this->validate($request, $rules);
        $restricted = RestrictSubDomain::find($request->input('id'));
        $restricted->update(['subdomain' => $request->subdomain]);
        return apiResponse(200, $request->subdomain . '.' . env('APP_URL') . ' restricted');
    }

    public function destroy(Request $request)
    {
        $rules = [
            'id' => 'required|exists:blacklist_sub_domains,id'
        ];
        $this->validate($request, $rules);
        $restricted = RestrictSubDomain::find($request->input('id'));
        $restricted->delete();
        return apiResponse(200, ' restricted domain has been restored');
    }
}
