<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessCategoryCtrl extends Controller
{
    /**
     * show bussiness Category Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return viewKlhk('back-office.page.master.business-category.index', 'new-backoffice.member.category_member');
    }

    /**
     * get data for datatable business category
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = BusinessCategory::all();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name_id="' . $model->business_category_name_id . '" data-name="' . $model->business_category_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name_id="' . $model->business_category_name_id . '" data-name="' . $model->business_category_name . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    /**
     * save new Business Category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'business_category_name' => 'required|unique:tbl_business_category,business_category_name',
            'business_category_name_id' => 'required|unique:tbl_business_category,business_category_name_id',
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $businessCategory = BusinessCategory::create([
                'business_category_name'=>$request->input('business_category_name'),
                'business_category_name_id'=>$request->input('business_category_name_id'),
            ]);
            msg($businessCategory->business_category_name.' created');
            \DB::commit();
            return redirect()->back();
        }catch (\Exception $exception){
            \DB::rollBack();
            msg('Something Wrong');
            return redirect()->back();
        }


    }

    /**
     * update business category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id'=>'required|exists:tbl_business_category,id',
            'business_category_name' => 'required|unique:tbl_business_category,business_category_name,'.$request->input('id'),
            'business_category_name_id' => 'required|unique:tbl_business_category,business_category_name_id,'.$request->input('id')
        ];
        $this->validate($request, $rule);
        $businessCategory = BusinessCategory::find($request->id);
        if ($businessCategory) {
            $businessCategory->update([
                'business_category_name'=>$request->input('business_category_name'),
                'business_category_name_id'=>$request->input('business_category_name_id'),
            ]);
            msg('Business Category Updated!');
            return redirect()->back();
        }
        msg('Business Category not found!', 2);
        return redirect()->back();
    }

    /**
     * delete business category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $businessCategory = BusinessCategory::find($request->id);
        if ($businessCategory) {
            $businessCategory->delete();
            msg('Business Category Deleted!');
            return redirect()->back();
        }
        msg('Business Category not found!', 2);
        return redirect()->back();
    }
}
