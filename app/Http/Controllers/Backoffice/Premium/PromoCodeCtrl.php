<?php

namespace App\Http\Controllers\Backoffice\Premium;

use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PromoCodeCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        toastr();
        return view('back-office.page.premium.promo-code.index');
    }

    /**
     * function load datatables promo code
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function loadAjaxData(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->wantsJson()) {
            $models = PromoCode::latest();
            return \DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('amount', function ($model){
                    if ($model->type == 'fixed'){
                        return format_priceID($model->amount,'IDR');
                    }
                    return $model->amount.'%';

                })
                ->addColumn('action', function ($model) {
                    $html = '<a href="'.route('admin:premium.promo-code.edit',['id'=>$model->id]).'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                    $html .= ' <button data-name="' . $model->code . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-trash"></i>';
                    $html .= '</button>';
                    return $html;
                })
                ->make(true);
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function add()
    {
        toastr();
        return view('back-office.page.premium.promo-code.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function save(Request $request)
    {

        if ($request->ajax() && $request->wantsJson()) {
            $request->merge([
                'amount' => str_replace('.', '', $request->input('amount')),
                'minimum_transaction'=>str_replace('.', '', $request->input('minimum_transaction')),
                'max_amount'=>str_replace('.', '', $request->input('max_amount')),
            ]);
            $rules = [
                'code' => [
                    'required',
                    'min:6',
                    'max:15',
                    Rule::unique('tbl_promo_code','code')->whereNull('deleted_at')
                ],
                'provider_max_use' => 'required|numeric',
                'general_max_use' => 'required|numeric',
                'type' => 'required|in:percentage,fixed',
                'amount' => 'required|numeric|min:1',
                'minimum_transaction' => 'required|numeric|min:0',
            ];
            if ($request->input('type') == 'percentage') {
                $rules['amount'] .= '|max:100';
                $rules['max_amount'] = 'required|numeric|min:1';
            }else{
                $rules['max_amount'] = 'required|numeric|min:1|max:'.$request->input('amount');
            }
            if (checkRequestExists($request, 'provider_max_use', 'POST')) {
                $rules['provider_max_use'] = 'required|numeric|min:1';
            }
            if (checkRequestExists($request, 'general_max_use', 'POST')) {
                $rules['general_max_use'] = 'required|numeric|min:1';
            }
            $this->validate($request, $rules);

            try {
                \DB::beginTransaction();
                $promo = new PromoCode();
                $promo->code = $request->input('code');
                $promo->provider_max_use = $request->input('provider_max_use');
                $promo->general_max_use = $request->input('general_max_use');
                $promo->type = $request->input('type');
                $promo->amount = $request->input('amount');
                $promo->max_amount = $request->input('max_amount');
                $promo->minimum_transaction = $request->input('minimum_transaction');
                $promo->is_always_available = $request->input('is_always_available') ? true : false;
                if (!$promo->is_always_available) {
                   $range = explode(' - ',$request->input('range'));
                   $start = Carbon::createFromFormat('d/m/Y',$range[0])->toDateTimeString();
                   $end = Carbon::createFromFormat('d/m/Y',$range[1])->toDateTimeString();
                   $promo->start_date = $start;
                   $promo->end_date = $end;

                }
                $promo->status=1;
                $promo->save();
                \DB::commit();
                return apiResponse(200,'Success add Promo Code',['redirect'=>route('admin:premium.promo-code.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500,'',getException($exception));
            }


        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function edit($id)
    {
        if (!$data['promo'] = PromoCode::find($id)){
            msg('Promo Code not found',2);
            return redirect()->route('admin:premium.promo-code.index');
        }
        $data['range'] = Carbon::now()->format('d/m/Y').' - '.Carbon::now()->format('d/m/Y');
        if ($data['promo']->start_date && $data['promo']->end_date){
            $data['range'] = Carbon::parse($data['promo']->start_date)->format('d/m/Y').' - '.Carbon::parse($data['promo']->end_date)->format('d/m/Y');
        }
        return view('back-office.page.premium.promo-code.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $id
     * @param  mixed $request
     *
     * @return void
     */
    public function update($id,Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            if (!$promo = PromoCode::find($id)) {
                msg('Promo Code not found', 2);
                return redirect()->route('admin:premium.promo-code.index');
            }
            $request->merge([
                'amount' => str_replace('.', '', $request->input('amount')),
                'minimum_transaction'=>str_replace('.', '', $request->input('minimum_transaction')),
                'max_amount'=>str_replace('.', '', $request->input('max_amount')),
            ]);
            $rules = [
//                'code' => 'required|min:6|max:15|unique:tbl_promo_code,code,'.$promo->id,
                'code' => [
                    'required',
                    'min:6',
                    'max:15',
                    Rule::unique('tbl_promo_code','code')->whereNull('deleted_at')->whereNot('id',$promo->id)
                ],
                'provider_max_use' => 'required|numeric',
                'general_max_use' => 'required|numeric',
                'type' => 'required|in:percentage,fixed',
                'amount' => 'required|numeric|min:1',
                'minimum_transaction' => 'required|numeric|min:0',
            ];
            if ($request->input('type') == 'percentage') {
                $rules['amount'] .= '|max:100';
                $rules['max_amount'] = 'required|numeric|min:1';
            }else{
                $rules['max_amount'] = 'required|numeric|min:1|max:'.$request->input('amount');
            }
            if (checkRequestExists($request, 'provider_max_use', 'POST')) {
                $rules['provider_max_use'] = 'required|numeric|min:1';
            }
            if (checkRequestExists($request, 'general_max_use', 'POST')) {
                $rules['general_max_use'] = 'required|numeric|min:1';
            }
            $this->validate($request, $rules);
            try {
                \DB::beginTransaction();
                $promo->code = $request->input('code');
                $promo->provider_max_use = $request->input('provider_max_use');
                $promo->general_max_use = $request->input('general_max_use');
                $promo->type = $request->input('type');
                $promo->amount = $request->input('amount');
                $promo->max_amount = $request->input('max_amount');
                $promo->minimum_transaction = $request->input('minimum_transaction');
                $promo->is_always_available = $request->input('is_always_available') ? true : false;
                if (!$promo->is_always_available) {
                    $range = explode(' - ',$request->input('range'));
                    $start = Carbon::createFromFormat('d/m/Y',$range[0])->toDateTimeString();
                    $end = Carbon::createFromFormat('d/m/Y',$range[1])->toDateTimeString();
                    $promo->start_date = $start;
                    $promo->end_date = $end;

                }else{
                    $promo->start_date =null;
                    $promo->end_date =null;
                }
                $promo->status=1;
                $promo->save();
                \DB::commit();
                return apiResponse(200,'Success update Promo Code',['redirect'=>route('admin:premium.promo-code.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500,'',getException($exception));
            }

        }
        abort(403);
    }

    /**
     * Remove the data guide from the specified resource from storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function delete(Request $request)
    {
        if (!$promo = PromoCode::find($request->id)) {
            msg('Promo Code not found', 2);
            return redirect()->route('admin:premium.promo-code.index');
        }
        $promo->delete();
        msg('Promo Code deleted');
        return redirect()->route('admin:premium.promo-code.index');
    }
}
