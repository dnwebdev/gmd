<?php

namespace App\Http\Controllers\Backoffice\Ota;

use App\Mail\Ota\ApprovedOtaEmail;
use App\Mail\Ota\RejectedOtaEmail;
use App\Models\Company;
use App\Models\Ota;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProductOtaController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.ota-product.index');
    }

    public function loadData(Request $request, Product $product)
    {
        $model = $product->newQuery()->with('ota', 'company')->has('ota');
        if (checkRequestExists($request, 'ota')) {
            $model->whereHas('ota', function ($ota) use ($request) {
                $ota->where('id', $request->get('ota'));
            });
        }
        if (checkRequestExists($request, 'provider')) {
            $model->whereHas('company', function ($company) use ($request) {
                $company->where('id_company', $request->get('provider'));
            });
        }
        if (checkRequestExists($request, 'status')) {
            $model->whereHas('ota', function ($ota) use ($request) {
                $ota->where('ota_products.status', $request->get('status'));
            });
        }
        $model->select('tbl_product.*');
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($m) {
                return '<button class="btn btn-detail btn-outline-info m-btn--icon-only"><i class="fa fa-chevron-down"></i></button>';
            })
            ->rawColumns(['action', 'ota_status'])
            ->make(true);
    }

    public function updateStatus(Request $request)
    {
        if (!$request->wantsJson()) {
            msg('Json Only', 2);
            return redirect()->route('admin:ota.list.index');
        }
        $rules = [
            'id' => 'required|exists:otas,id',
            'product' => [
                'required',
                Rule::exists('tbl_product', 'id_product')->whereNull('deleted_at')->where('booking_type', 'online')
                    ->where('status', 1)
            ],
            'status' => 'required|in:approve,reject'
        ];
        if (checkRequestExists($request,'status')){
            if ($request->get('status') ==='reject'){
                $rules['reject_reason'] = 'required|min:10|max:1000';
                $rules['reject_reason_en'] = 'required|min:10|max:1000';
            }
        }
        $this->validate($request, $rules);
        $productOta = \DB::table('ota_products')
            ->where('ota_id', $request->id)
            ->where('product_id', $request->product);
        if (!$productOta->first()) {
            return apiResponse(404, __('notification.product.not_found'));
        }
        $response = [
            'approve' => 1,
            'reject' => 2
        ];
        $productOta->update(['status' => $response[$request->status],'reject_reason'=>$request->get('reject_reason'),'reject_reason_en'=>$request->get('reject_reason')]);
        $company = Company::whereHas('products', function ($p) use ($request) {
            $p->where('id_product', $request->product);
        })->first();
        $product = Product::find($request->product);
        $ota = Ota::find($request->id);
        if ($company && $product && $ota && !empty($company->email_company)) {
            if ($productOta->first()->status === 1) {
                \Mail::to($company->email_company)->sendNow(new ApprovedOtaEmail($product, $ota));
            } else {
                \Mail::to($company->email_company)->sendNow(new RejectedOtaEmail($product,$ota,$request->reject_reason,$request->reject_reason_en));
            }
        }

        return apiResponse(200, 'OK', $response[$request->status]);

    }
}
