<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\Addon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class AddonCtrl extends Controller
{
    /**
     * see addon pages
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return view('back-office.page.master.addon.index');
    }

    /**
     * get data add on for datatables
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = Addon::all();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->add_name . '"data-nameindo="' . $model->add_name_indo . '"  data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name="' . $model->add_name . '" data-nameindo="' . $model->add_name_indo . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    /**
     * save add on
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'add_name' => 'required|unique:tbl_addon,add_name',
            'add_name_indo' => 'required|unique:tbl_addon,add_name_indo',
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $lang = Addon::create(['add_name'=>$request->input('add_name'),'add_name_indo'=>$request->input('add_name_indo'),'status'=>'active']);
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

    /**
     * update addon
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id'=>'required|exists:tbl_addon,id',
            'add_name' => [
                'required',
                Rule::unique('tbl_addon','add_name')->whereNot('id',$request->id)
            ],
            'add_name_indo' => [
                'required',
                Rule::unique('tbl_addon','add_name_indo')->whereNot('id',$request->id)
            ]
        ];
        $this->validate($request, $rule);
        $lang = Addon::find($request->id);
        if ($lang) {
            $lang->update(['add_name'=>$request->input('add_name'),'add_name_indo'=>$request->input('add_name_indo'),'status'=>'active']);
            msg('Addon Updated!');
            return redirect()->back();
        }
        msg('Addon not found!', 2);
        return redirect()->back();
    }

    /**
     * delete add on
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $lang = Addon::find($request->id);
        if ($lang) {
            $lang->delete();
            msg('Addon Deleted!');
            return redirect()->back();
        }
        msg('Addon not found!', 2);
        return redirect()->back();
    }
}
