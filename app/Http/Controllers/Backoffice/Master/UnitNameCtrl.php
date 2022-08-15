<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\UnitName;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UnitNameCtrl extends Controller
{
    public function index(Request $request)
    {
        toastr();
        return view('back-office.page.master.unit_name.index');
    }

    public function loadData()
    {
        $models = UnitName::query();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                 $html = '<button data-name-en="' . $model->name_en . '" data-name-id="' . $model->name_id .'" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name-en="' . $model->name_en . '" data-name-id="' . $model->name_id . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    public function save(Request $request)
    {
        $rule = [
            'name_en' => 'required',
            'name_id' => 'required',
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $lang = UnitName::create([
                'name_en'   => $request->input('name_en'),
                'name_id'   => $request->input('name_id'),
                'is_active' => 1
            ]);
            msg($lang->name.' created');
            \DB::commit();
            return redirect()->back();
        }catch (\Exception $exception){
            \DB::rollBack();
            dd($exception);
            msg('Something Wrong');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $rule = [
            'id'        => 'required|exists:unit_names,id',
            'name_en'   => 'required',
            'name_id'   => 'required'
        ];
        $this->validate($request, $rule);
        $lang = UnitName::find($request->id);
        if ($lang) {
            $lang->update([
                'name_en'   => $request->input('name_en'),
                'name_id'   => $request->input('name_id')
            ]);
            msg('Unit Name Updated!');
            return redirect()->back();
        }
        msg('Unit Name not found!', 2);
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $lang = UnitName::find($request->id);
        if ($lang) {
            $lang->delete();
            msg('Unit Name Deleted!');
            return redirect()->back();
        }
        msg('Unit Name not found!', 2);
        return redirect()->back();
    }
}
