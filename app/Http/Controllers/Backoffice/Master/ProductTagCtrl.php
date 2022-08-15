<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\ProductTax;
use App\Models\TagProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProductTagCtrl extends Controller
{
    /**
     * show Product Tag page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return viewKlhk('back-office.page.master.product-tag.index', 'new-backoffice.list.product_tag');
    }

    /**
     * show Product Tag page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ListTag($tag_id)
    {
        toastr();
        $tag = TagProduct::findOrFail($tag_id);
        return view('new-backoffice.list.list_product_tag', compact('tag'));
    }

    /**
     * provide data for product tag
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $is_klhk = request()->is_klhk;
        $models = TagProduct::when($is_klhk, function ($query, $is_klhk) {
            return $query->whereHas('products.company', function ($query) {
                return $query->where('is_klhk', request()->is_klhk);
            });
        })->withCount('products');
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                if (request()->is_klhk) {
                    $html = '<a href="'.route('admin:product.index', ['tag' => $model->id]).'"  data-popup="tooltip" title="Lihat"><i class="icon-eye"></i></a>';
                } else {
                     $html = '<button data-name="' . $model->name . '" data-nameindo="' . $model->name_ind .'" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                    $html .= ' <button data-name="' . $model->name . '" data-nameindo="' . $model->name_ind . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-trash"></i>';
                    $html .= '</button>';
                }
                return $html;
            })
            ->make(true);
    }

    /**
     * save new product tag
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|unique:tbl_tag_products,name',
            'name_ind' => 'required|unique:tbl_tag_products,name_ind',
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $lang = TagProduct::create(['name'=>$request->input('name'),'name_ind'=>$request->input('name_ind'),'status'=>1]);
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
     * update product tag
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id'=>'required|exists:tbl_tag_products,id',
            'name' => [
                'required',
                Rule::unique('tbl_tag_products','name')->whereNot('id',$request->id)
            ],
            'name_ind' => [
                'required',
                Rule::unique('tbl_tag_products','name_ind')->whereNot('id',$request->id)
            ]
        ];
        $this->validate($request, $rule);
        $lang = TagProduct::find($request->id);
        if ($lang) {
            $lang->update(['name'=>$request->input('name'),'name_ind'=>$request->input('name_ind')]);
            msg('Tag Product Updated!');
            return redirect()->back();
        }
        msg('Tag Product not found!', 2);
        return redirect()->back();
    }

    /**
     * delete product tag
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $lang = TagProduct::find($request->id);
        if ($lang) {
            $lang->delete();
            msg('Tag Product Deleted!');
            return redirect()->back();
        }
        msg('Tag Product not found!', 2);
        return redirect()->back();
    }
}
