<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\CategoryPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryPaymentCtrl extends Controller
{
    /**
     * show bussiness Category Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return view('back-office.page.master.category-payment.index');
    }

    /**
     * get data for datatable business category
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = CategoryPayment::all();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name_third_party="'.$model->name_third_party.'" data-name_third_party_eng="'.$model->name_third_party_eng.'" data-id="'.$model->id.'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name_third_party="'.$model->name_third_party.'" data-name_third_party_eng="'.$model->name_third_party_eng.'" data-id="'.$model->id.'" class="btn-delete btn btn-outline-danger  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    /**
     * save new Category Payment
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'name_third_party' => 'required|unique:category_payment,name_third_party',
            'name_third_party_eng' => 'required|unique:category_payment,name_third_party_eng'
        ];
        $this->validate($request, $rule);
        try {
            \DB::beginTransaction();
            $category = CategoryPayment::create([
                'name_third_party' => $request->input('name_third_party'),
                'name_third_party_eng' => $request->input('name_third_party_eng')
            ]);
            msg($category->name_third_party.' created');
            \DB::commit();
            return redirect()->back();
        } catch (\Exception $exception) {
            \DB::rollBack();
            msg('Something Wrong');
            return redirect()->back();
        }


    }

    /**
     * update business category
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id' => 'required|exists:category_payment,id',
            'name_third_party' => 'required|unique:category_payment,name_third_party,'.$request->input('id'),
            'name_third_party_eng' => 'required|unique:category_payment,name_third_party_eng,'.$request->input('id')
        ];
        $this->validate($request, $rule);
        $category = CategoryPayment::find($request->id);
        if ($category) {
            $category->update([
                'name_third_party' => $request->input('name_third_party'),
                'name_third_party_eng' => $request->input('name_third_party_eng')
            ]);
            msg('Category Payment Updated!');
            return redirect()->back();
        }
        msg('Category Payment not found!', 2);
        return redirect()->back();
    }

    /**
     * delete business category
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $category = CategoryPayment::find($request->id);
        if ($category) {
            $category->delete();
            msg('Category Payment Deleted!');
            return redirect()->back();
        }
        msg('Category Payment not found!', 2);
        return redirect()->back();
    }
}
