<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Enums\ManualTransferStatus;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\ManualTransfer;

class ProviderManualTransferCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.master.manual-transfer.index');
    }

    public function loadData()
    {
        $models = ManualTransfer::orderBy('created_at', 'desc')->get();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('company', function($model) {
                return optional($model->company)->company_name;
            })
            ->editColumn('image', function ($model) {
                return '<img width=50 src="'.asset('uploads/bank_manual/'.$model->upload_document).'">';
            })
            ->addColumn('action', function ($model) {
                $html = '<a href="'.route('admin:master.provider-manual-transfer.edit',
                        ['id' => $model->id]).'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-edit"></i></a>';
                return $html;
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    public function download($id)
    {
        $tes = ManualTransfer::find($id);
        return response()->download(public_path('uploads/bank_manual/'.$tes->upload_document));
    }

    public function create()
    {
        $blog = ManualTransfer::all();
        return view('back-office.page.master.manual-transfer.create',
            compact(
                'categorys', 'blog'
            ));
    }


    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $rule = [
            'category_payment_id' => 'required',
            'name_payment' => 'required|max:255',
            'name_payment_eng' => 'required|max:255',
            'code_payment' => 'required|max:255|unique:payment_list,code_payment',
            'image_payment' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif',
            'status' => 'nullable|digits_between:0,1',
            'type' => 'required|in:percentage,fixed',
            'type_secondary' => 'required|in:percentage,fixed',
            'pricing_primary' => 'required|numeric|max:100',
            'pricing_secondary' => 'required|numeric|max:100',
            'settlement_duration' => 'required|numeric|min:0',
        ];
        if ($request->input('type') == 'fixed') {
            $rule['pricing_primary'] = 'required|numeric|min:0';
            $request->merge([
                'pricing_primary' => str_replace('.', '', $request->input('pricing_primary')),
            ]);
//            $pricing_primary = $request->pricing_primary_fixed;
        }
        if ($request->input('type_secondary') == 'fixed') {
            $rule['pricing_secondary'] = 'required|numeric|min:0';
            $request->merge([
                'pricing_secondary' => str_replace('.', '', $request->input('pricing_secondary')),
            ]);
        }

        $this->validate($request, $rule);

        try {
            \DB::beginTransaction();
            $list = new ListPayment();
            $list->category_payment_id = $request->category_payment_id;
            $list->name_payment = $request->name_payment;
            $list->name_payment_eng = $request->name_payment_eng;
            $list->code_payment = preg_replace('/\s+/', '_', $request->code_payment);
            $list->status = $request->status ? $request->status : 0;
            $list->type = $request->type;
            $list->type_secondary = $request->type_secondary;
            $list->pricing_primary = $request->pricing_primary;
            $list->pricing_secondary = $request->pricing_secondary;
            $list->settlement_duration = $request->settlement_duration;
            $list->save();
            if ($request->hasFile('image_payment')) {
                $image = $request->file('image_payment');
                $path = storage_path('app/public/uploads/payment/');
                $name = time().'-'.$list->name_payment.'.'.$image->getClientOriginalExtension();
                if (!\File::isDirectory($path)) {
                    \File::makeDirectory($path, 0777, true, true);
                }
                if (\Image::make($image)->save($path.$name)) {
                    $deleteIcon = $list->icon;
                    $list->image_payment = 'storage/uploads/payment/'.$name;
                }
            }
            $list->companies()->sync(Company::all());
            foreach ($list->companies as $data) {
                if (in_array($list->code_payment, ['credit_card','dana','linkaja','indomaret','gopay','bca_va'])){
                    $list->companies()->updateExistingPivot($data->id_company, ['charge_to' => 1]);
                }
            }
            $list->save();
            \DB::commit();
            return apiResponse(200, 'Payment List Success', ['redirect' => route('admin:master.provider-manual-transfer.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $list = ManualTransfer::find($id);
        return view('back-office.page.master.manual-transfer.edit', compact('list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $rule = [
                'name_rekening' => 'required|max:255',
                'no_rekening' => 'required|max:255',
                'code_payment' => 'required',
                'status' => 'required',
            ];

            $this->validate($request, $rule);

            try {
                \DB::beginTransaction();
                $list = ManualTransfer::find($id);
                $list->name_rekening = $request->name_rekening;
                $list->no_rekening = $request->no_rekening;
                $list->code_payment = $request->code_payment;
                $list->status = $request->status;
                $list->save();
                \DB::commit();
                return apiResponse(200, 'Success',
                    ['redirect' => route('admin:master.provider-manual-transfer.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        abort(403);
    }

    public function active(Request $request)
    {
        if (!$list = ManualTransfer::find($request->id)) {
            return apiResponse(404, 'Payment List not found');
        }
        if ($list->status != 1) {
            $list->update(['status' => 1]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Active');
    }

    public function nonactive(Request $request)
    {
        if (!$list = ManualTransfer::find($request->id)) {
            return apiResponse(404, 'Payment List Found');
        }
        if ($list->status != 0) {
            $list->update(['status' => 0]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Non Active');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $del = ManualTransfer::find($request->id);
        if (!empty($del->image_payment)) {
            \Storage::delete('public/uploads/blog/'.$del->image_payment);
            $del->delete();
        } else {
            $del->delete();
        }
        msg('Payment List Deleted!');
        return redirect()->back();

    }
}
