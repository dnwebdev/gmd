<?php

namespace App\Http\Controllers\Backoffice\Outsider;

use App\Imports\OfflineOrderImport;
use App\Models\Hhbk;
use App\Models\OfflineOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class OutsideController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.outside.index');
    }

    public function getData()
    {
        $model = OfflineOrder::query()->with(['company']);
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:hhbk-distribution.edit', ['id' => $model->id]) . '" data-name="' . $model->product_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
                $html .= ' <button  data-name="' . $model->product_name . '" data-id="' . $model->id . '"  class="btn btn-outline-danger btn-delete btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-delete"></i></button>';
                return $html;
            })
            ->make(true);
    }

    public function add()
    {
        toastr();
        return view('back-office.outside.add');
    }

    public function save(Request $request)
    {
        $rules = [
            'hhbk_id' => 'required|exists:hhbks,id',
            'amount' => 'required|numeric|min:1000',
            'client' => 'required|in:tokopedia,shopee'
        ];
        $this->validate($request, $rules);
        $p = Hhbk::find($request->input('hhbk_id'));
        OfflineOrder::create([
            'productable_id' => $request->input('hhbk_id'),
            'productable_type' => Hhbk::class,
            'company_id' => $p ? $p->company ? $p->company->id_company : null : null,
            'product_name' => $p->product_name,
            'amount' => $request->input('amount'),
            'channel' => 'distribution',
            'client' => $request->input('client')
        ]);
        msg('penambahan data transaksi HHBK sukses');
        return redirect()->route('admin:hhbk-distribution.index');
    }

    public function importExcell(Request $request)
    {
        $rules = [
            'upload' => 'required|file'
        ];
        $this->validate($request, $rules);
        $excell = $request->file('upload');
        $array = new OfflineOrderImport();
        $a = $array->import($excell);
        return redirect()->back();
    }

    public function edit(Request $request)
    {
        toastr();
        if (!$order = OfflineOrder::whereId($request->id)->where('productable_type', Hhbk::class)->first()):
            msg('Order not found');
            return redirect()->route('admin:hhbk-distribution.index');
        endif;
        return view('back-office.outside.edit', compact('order'));
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|exists:offline_orders,id',
            'hhbk_id' => 'required|exists:hhbks,id',
            'amount' => 'required|numeric|min:1000',
            'client' => 'required|in:tokopedia,shopee'
        ];
        $this->validate($request, $rules);
        if (!$order = OfflineOrder::whereId($request->id)->where('productable_type', Hhbk::class)->first()):
            msg('Order not found');
            return redirect()->route('admin:hhbk-distribution.index');
        endif;
        $order->update([
            'productable_id' => $request->input('hhbk_id'),
            'amount'=>$request->input('amount'),
            'client'=>$request->input('client')
        ]);
        msg('updated');
        return redirect()->route('admin:hhbk-distribution.index');
    }

    public function delete(Request $request)
    {
        if (!$order = OfflineOrder::whereId($request->id)->where('productable_type', Hhbk::class)->first()):
            msg('Order not found');
            return redirect()->route('admin:hhbk-distribution.index');
        endif;
        $order->delete();
        msg('deleted');
        return redirect()->route('admin:hhbk-distribution.index');
    }
}
