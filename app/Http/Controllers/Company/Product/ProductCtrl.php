<?php

namespace App\Http\Controllers\Company\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCtrl extends Controller
{
    /**
     * Displays the list of products from resources.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }
        toastr();
        $haveOta = \App\Models\Ota::where('ota_status', true)->exists();
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.newindex', compact('haveOta'));
        }
        return view('dashboard.company.product.newindex', compact('haveOta'));
    }

    /**
     * function load data list product
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function loadData($request)
    {
        $company = auth('web')->user()->company;
        $haveOta = \App\Models\Ota::where('ota_status', true)->exists();

        $products = Product::query()
            ->with('product_type', 'city', 'ota')
            ->select('tbl_product.*')
            ->where('id_company', $company->id_company)
            ->withCount('ota');
        if ($request->has('type') && $request->type !== null && $request->type !== '') {
            $products = $products->whereHas('product_type', function ($product_type) use ($request) {
                $product_type->where('id_tipe_product', $request->type);
            });
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $products = $products->where('status', $request->status);
        }else{
            $products = $products->orderBy('status','desc');
        }

        return \DataTables::of($products)
            ->editColumn('unique_code', function ($model){
                return '<a href="javascript:void(0);" class="unique">'.$model->unique_code.'</a>';
            })
            ->editColumn('product_name', function ($model) use ($haveOta) {
                $html = '<a href="' . route('company.product.edit', ['product' => $model]) . '" class="d-none d-md-block product_name text-truncate" style="width: 10rem">' . $model->product_name . '</a>';
                $html .= '<span class="d-inline-block d-sm-none text-truncate" style="width: 10rem">' . $model->product_name . '</span>';

                $html .= '<div class="dropdown float-right d-block d-sm-none">
                        <button class="btn btn-link p-0 m-0 no-border shadow-none" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars text-dark" style="font-size: 14px"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item btn-generate" href="javascript:void(0);" data-domain="'.$model->company->domain_memoria.'" data-id="'.$model->unique_code.'" data-toggle="modal" data-target="#generate_widget_modal">'.trans('product_provider.generate').'</a>
                                <a class="dropdown-item" href="'.route('company.product.edit', ['product' => $model]).'">'.__('general.edit').'</a>
                                <a class="dropdown-item" href="'.route('company.product.duplicateForm',['id'=>$model->id_product]).'">'.__('general.duplicate').'</a>
                                <a class="dropdown-item delete" href="javascript:void(0);" data-id="'.$model->id_product.'" data-toggle="modal" data-target="#modal-delete-product">'.__('general.delete').'</a>
                                <a class="dropdown-item btn-edit-ota'.(!$haveOta ? ' d-none' : '').'" href="javascript:void(0);" data-id="'.$model->id_product.'"
                                    data-selected="'.$model->ota->implode('id', ',').'"
                                    data-approved="'.$model->ota->where('pivot.status', 1)->implode('id', ',').'"
                                    data-rejected="'.$model->ota->where('pivot.status', 2)->implode('id', ',').'"
                                    data-toggle="modal"
                                    data-target="#ota-modal">'.trans('product_provider.send_ota').' <span class="badge ml-2" style="background-color:#f8d151; color: #2a3040">'.$model->ota_count.'</span></a>
                                <a class="dropdown-item btn-export" href="#" role="button" data-toggle="modal" data-target="#export-excel-modal" data-id="'.$model->id_product.'">'.__('product_provider.export_order.modal.title').'</a>
                            </div>
                        </div>';

                return $html;
            })
            ->addColumn('action', function ($model) {
                $return = '<div class="width-span"><button class="btn btn-widget btn-generate btn-sm btn-primary tooltips" style="
                color: #fff;
                font-weight: 400;
                padding: .25rem 1.5rem;
                font-size: .875rem;
                height: 1.7rem;
                line-height: .5;
                background-color: #0893cf;
                margin-bottom: 0;
                border-color: #0893cf;
                text-transform: none;
                border-radius: 4px;
                position: relative;"
                data-domain="'.$model->company->domain_memoria.'"
                data-id="'.$model->unique_code.'"
                data-toggle="modal" data-target="#generate_widget_modal"
                data-tooltip-content="#tooltipWidgetContent"> 
                '.trans('product_provider.generate').'</button>
                <a target="_blank" href="'.route('company.product.duplicateForm',['id'=>$model->id_product]).'" class="btn btn-copy btn-sm btn-product-list-unified tooltips"
                data-id="'.$model->id_product.'" data-tooltip-content="#tooltipDuplicateContent"
                ><i class="fa fa-clone"></i></a>
                <button class="btn btn-success btn-sm btn-export btn-product-list-unified tooltips" style="
                font-weight: 400;
                padding: .25rem .32rem;
                font-size: .875rem;
                line-height: .7;
                margin-bottom: 0;
                background: green;
                border: 1px solid green;
                box-shadow: none;
                height: 1.75rem;"
                data-toggle="modal"
                data-target="#export-excel-modal"
                data-id="'.$model->id_product.'" data-tooltip-content="#tooltipExportContent"><i class="fa fa-download" style="color: #0893cf"></i></button>';

                if ($model->quota_type == 'day') {
                    $return .= ' <button class="view-order btn btn-sm btn-product-list-unified tooltips" style="font-weight: 400;
                    padding: .25rem .32rem;
                    font-size: .875rem;
                    line-height: .7;
                    margin-bottom: 0;
                    height: 1.75rem;
                    color: #0893cf;"
                    data-toggle="modal"
                    data-target="#order-date-modal"
                    data-id="'.$model->id_product.'" data-tooltip-content="#tooltipOrderDataContent">
                    <i class="fa fa-calendar" style="font-size: 1rem;"></i>
                    </button>';
                }

                $return .= '<button class="btn btn-delete btn-sm btn-danger btn-product-list-unified tooltips" style="
                color: #c82333;
                font-weight: 400;
                padding: 3px 0 4px;
                font-size: .875rem;
                line-height: .7;
                margin-bottom: 0;
                background: none;
                border: none;
                box-shadow: none;"
                data-id="'.$model->id_product.'" data-tooltip-content="#tooltipDeleteContent"
                data-toggle="modal"
                data-target="#modal-delete-product"
                ><i class="fa fa-trash"></i></button></div>';

                return $return;
            })
            ->addColumn('booked', function ($model){
                $return = $model->order_details()->whereHas('invoice', function ($i){
                    $i->whereIn('status',[0,1]);
                })->sum('adult');

                if ($model->quota_type == 'periode') {
                    $return .= '/'.$model->max_people;
                }

                return $return;
            })
            ->editColumn('ota_count', function ($model) { 
                $return = '<div class="ota-container"><button class="btn btn-edit-ota btn-sm btn-primary" style="
                color: #fff;
                font-weight: 400;
                padding: .25rem 1rem;
                font-size: .875rem;
                height: 1.7rem;
                line-height: .5;
                margin-bottom: 0;
                background-color: #3f93cf;
                border-color: #3f93cf;
                border-radius: 4px;
                text-transform: none;
                padding-left: 2rem;
                position: relative;
                overflow: visible;"
                data-id="'.$model->id_product.'"
                data-selected="'.$model->ota->implode('id', ',').'"
                data-approved="'.$model->ota->where('pivot.status', 1)->implode('id', ',').'"
                data-rejected="'.$model->ota->where('pivot.status', 2)->implode('id', ',').'"
                data-toggle="modal"
                data-target="#ota-modal">
                <i class="fa fa-external-link-square"></i>
                '.trans('product_provider.send_ota').'<span class="ota" style="
                position: absolute;
                background-color: #f8d151;
                padding: 6px 10px;
                font-size: .7rem;
                top: 50%;
                transform: translateY(-50%);
                border-radius: 3px;
                right: -45px;
                color: #2a3040;
                ">'.trans_choice('product_provider.otas', $model->ota_count, ['value' =>  $model->ota_count]).'</span></button></div>';
                return $return;
            })
            ->rawColumns(['product_name', 'action', 'ota_count','unique_code'])
            ->make(true);
    }

    /**
     * Remove the data product from the specified resource from storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function deleteProduct(Request $request)
    {
        if ($product = Product::where('id_product',$request->id)->where('id_company',auth('web')->user()->company->id_company)){
            $product->delete();
            return apiResponse(200,trans('general.success_delete'));
        }
        return apiResponse(404,trans('notification.product.not_found'));
    }

    /**
     * Update OTA
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateOTA(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:tbl_product,id_product',
            'ota'   => 'array'
        ]);

        if ($product = Product::where(['id_product' => $request->input('id'), 'id_company' => $request->user()->company->id_company])->first()) {
            $product->ota()->sync($request->input('ota', []));
            return apiResponse(200, 'OK');
        }

        return apiResponse(404, trans('notification.product.not_found'));
    }
}
