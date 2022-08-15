<?php

namespace App\Http\Controllers\Backoffice\Voucher;

use App\Models\Company;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class VoucherGomodoCtrl extends Controller
{
    /**
     * show page Voucher General
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        \toastr();
        $thisweekStart = \Carbon\Carbon::now()->startOfWeek()->toDateTimeString();
        $lastweekStart = \Carbon\Carbon::now()->subWeek(1)->startOfWeek()->toDateTimeString();

        $reimburseVoucherthisWeek = \App\Models\Order::whereHas('voucher', function ($v) {
            $v->where('by_gomodo', 1);    })
            ->whereHas('payment', function ($payment) use ($thisweekStart,$lastweekStart){
                $payment->where('payment_gateway','!=','Cash On Delivery')
                    ->where('updated_at','>=',$thisweekStart)
                    ->where('updated_at','<',\Carbon\Carbon::now()->toDateTimeString())
                    ->where('status','PAID');
            })
            ->where('status', 1)
            ->sum('voucher_amount');
        $reimburseVoucherlastWeek = \App\Models\Order::whereHas('voucher', function ($v) {
            $v->where('by_gomodo', 1);
        })
            ->whereHas('payment', function ($payment) use ($thisweekStart,$lastweekStart){
                $payment->where('payment_gateway','!=','Cash On Delivery')
                    ->where('updated_at','>=',$lastweekStart)
                    ->where('updated_at','<',$thisweekStart)
                    ->where('status','PAID');
            })
            ->where('status', 1)
            ->sum('voucher_amount');
        return view('back-office.page.voucher-gomodo.index',compact('reimburseVoucherlastWeek','reimburseVoucherthisWeek'));
    }

    /**
     * provide data Voucher General
     * @return mixed
     * @throws \Exception
     */
    public function loadAjaxData()
    {
        $where = [
            'by_gomodo' => 1,
//            'tbl_voucher.status' => 1,
        ];
        $model = Voucher::where($where)
            ->with('company')
            ->withCount('order_paid');


        return \DataTables::of($model)
            ->addIndexColumn()

            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:voucher-gomodo.edit', ['id' => $model->id_voucher]) . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                if ($model->status == "Active") {
                    $html .= ' <button data-name="' . $model->voucher_code . '" data-id="' . $model->id_voucher . '" class="btn-disable btn btn-outline-success  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-up"></i> Active';
                    $html .= '</button>';
                } else {
                    $html .= ' <button data-name="' . $model->voucher_code . '" data-id="' . $model->id_voucher . '" class="btn-enable btn btn-outline-danger  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-down"></i> Non Active';
                    $html .= '</button>';
                }

                return $html;
            })
            ->make(true);
    }

    /**
     * show page add voucher gomodo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        toastr();
        return view('back-office.page.voucher-gomodo.add');
    }

    /**
     * show page edit voucher
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        if (!$data['voucher'] = Voucher::find($id)) {
            msg('Voucher not found', 2);
            return redirect()->route('admin:voucher-gomodo.index');
        }
        toastr();
        $data['company'] = $data['voucher']->company()->pluck('company_name', 'id_company');
        return view('back-office.page.voucher-gomodo.edit', $data);
    }

    /**
     * reimbursement to Providers
     * @return \Illuminate\Http\JsonResponse
     */
    public function reimbursementGomodo()
    {

        try{
            \DB::beginTransaction();
            \App\Models\Order::whereHas('payment', function ($q) {
                $q->where(['status' => 'PAID']);        })
                ->whereHas('voucherGomodo')
                ->where('reimbursement',0)->update(['reimbursement'=>1]);
            \DB::commit();
            return apiResponse(200,'OK');
        }catch (\Exception $exception){
            \DB::rollBack();
            return apiResponse(500,'',getException($exception));
        }

    }

    /**
     * search Provider
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProvider(Request $request)
    {

        $q = $request->input('q');
        $result = Company::select('id_company', 'company_name', 'domain_memoria', 'logo')
            ->where('company_name', 'like', '%' . $q . '%')
            ->orWhere('domain_memoria', 'like', '%' . $q . '%')
            ->orWhere('email_company', 'like', '%' . $q . '%');

        $data = [];
        foreach ($result->paginate(10) as $item) {

            $data[] = [
                'id' => $item->id_company,
                'text' => $item->company_name,
                'logo' => \File::exists(asset('uploads/company_logo/' . $item->logo)) ? asset('uploads/company_logo/' . $item->logo) : \File::exists(asset($item->logo)) ? asset($item->logo) : asset('img/no-product-image.png'),
                'domain_memoria' => $item->domain_memoria
            ];
        }
        return response()->json([
            'incomplete_results' => false,
            'items' => $data,
            'total_count' => $result->count()
        ]);
    }

    /**
     * add new Voucher
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $request->merge([
                'voucher_amount' => str_replace('.', '', $request->input('voucher_amount')),
                'minimum_amount' => str_replace('.', '', $request->input('minimum_amount')),
            ]);
            $rules = [
                'id_company' => [
                    'required',
                    Rule::exists('tbl_company', 'id_company')
                ],
                'voucher_code' => [
                    'required',
                    'min:6',
                    'max:15',
                    Rule::unique('tbl_voucher', 'voucher_code')->where('id_company', '!=', $request->input('id_company'))
                ],
                'voucher_amount_type' => 'required|in:percentage,fixed',
                'voucher_amount' => 'required|numeric|min:1',
                'minimum_amount' => 'required|numeric|min:0',
            ];
            if ($request->input('voucher_amount_type') == 'percentage') {
                $rules['voucher_amount'] .= '|max:100';
            } else {
                $rules['voucher_amount'] .= '|max:' . $request->input('minimum_amount');
            }
            if ($request->has('up_to') && $request->input('up_to') != '') {
                $request->merge([
                    'up_to' => str_replace('.', '', $request->input('up_to'))
                ]);
                if ($request->input('voucher_amount_type') == 'percentage') {
                    $rules['up_to'] = 'required|numeric|min:1';
                } else {
                    $rules['up_to'] = 'required|numeric|min:1|max:' . $request->input('voucher_amount');
                }

            }
            if ($request->has('max_use') && $request->input('max_use') != '') {
                $request->merge([
                    'max_use' => str_replace('.', '', $request->input('max_use'))
                ]);
                $rules['max_use'] = 'required|numeric|min:1';
            }
            $this->validate($request, $rules);
            try {
                \DB::beginTransaction();
                $data = [
                    'id_company' => $request->input('id_company'),
                    'voucher_code' => $request->input('voucher_code'),
                    'voucher_type' => 1,
                    'voucher_description' => 'Gomodo Voucher',
                    'currency' => 'IDR',
                    'voucher_amount_type' => $request->input('voucher_amount_type') == 'percentage' ? 1 : 0,
                    'voucher_amount' => $request->input('voucher_amount'),
                    'minimum_amount' => $request->input('minimum_amount'),
                    'up_to' => $request->input('up_to'),
                    'max_use' => $request->input('max_use'),
                    'valid_start_date' => Carbon::now()->toDateString(),
                    'valid_end_date' => Carbon::now()->addMonth(1)->toDateString(),
                    'start_date' => Carbon::now()->toDateString(),
                    'end_date' => Carbon::now()->addMonth(1)->toDateString(),
                    'status' => !empty($request->get('status')) ? $request->get('status') : true,
                    'updated_by' => $request->input('id_company'),
                    'by_gomodo' => 1,
                ];
                Voucher::create($data);
                \DB::commit();

                return apiResponse(200, 'Voucher Created', ['redirect' => route('admin:voucher-gomodo.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, 'KO', getException($exception));
            }


        }
    }

    /**
     * update voucher
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function update($id, Request $request)
    {

        if ($request->ajax() && $request->wantsJson()) {
            if (!$voucher = Voucher::find($id)) {
                return apiResponse(404, 'Not Found');
            }
            $request->merge([
                'voucher_amount' => str_replace('.', '', $request->input('voucher_amount')),
                'minimum_amount' => str_replace('.', '', $request->input('minimum_amount')),
            ]);
            $rules = [
                'id_company' => [
                    'required',
                    Rule::exists('tbl_company', 'id_company')
                ],
                'voucher_code' => [
                    'required',
                    'min:6',
                    'max:15',
                    Rule::unique('tbl_voucher', 'voucher_code')->whereNot('id_voucher', $id)->whereNot('id_company', $request->id_company)
                ],
                'voucher_amount_type' => 'required|in:percentage,fixed',
                'voucher_amount' => 'required|numeric|min:1',
                'minimum_amount' => 'required|numeric|min:0',
            ];
            if ($request->input('voucher_amount_type') == 'percentage') {
                $rules['voucher_amount'] .= '|max:100';
            } else {
                $rules['voucher_amount'] .= '|max:' . $request->input('minimum_amount');
            }
            if ($request->has('up_to') && $request->input('up_to') != '') {
                $request->merge([
                    'up_to' => str_replace('.', '', $request->input('up_to'))
                ]);
                if ($request->input('voucher_amount_type') == 'percentage') {
                    $rules['up_to'] = 'required|numeric|min:1';
                } else {
                    $rules['up_to'] = 'required|numeric|min:1|max:' . $request->input('voucher_amount');
                }

            }
            if ($request->has('max_use') && $request->input('max_use') != '') {
                $request->merge([
                    'max_use' => str_replace('.', '', $request->input('max_use'))
                ]);
                $rules['max_use'] = 'required|numeric|min:1';
            }
            $this->validate($request, $rules);
            try {
                \DB::beginTransaction();
                $data = [
                    'id_company' => $request->input('id_company'),
                    'voucher_code' => $request->input('voucher_code'),
                    'voucher_type' => 1,
                    'voucher_description' => 'Gomodo Voucher',
                    'currency' => 'IDR',
                    'voucher_amount_type' => $request->input('voucher_amount_type') == 'percentage' ? 1 : 0,
                    'voucher_amount' => $request->input('voucher_amount'),
                    'minimum_amount' => $request->input('minimum_amount'),
                    'up_to' => $request->input('up_to'),
                    'max_use' => $request->input('max_use'),
//                    'valid_start_date' => Carbon::now()->toDateString(),
//                    'valid_end_date' => Carbon::now()->addYears(2)->toDateString(),
//                    'start_date' => Carbon::now()->toDateString(),
//                    'end_date' => Carbon::now()->addYears(2)->toDateString(),
                    'status' => !empty($request->get('status')) ? $request->get('status') : true,
                    'updated_by' => $request->input('id_company'),
                    'by_gomodo' => 1,
                ];
                $voucher->update($data);
                \DB::commit();

                return apiResponse(200, 'Voucher Updated', ['redirect' => route('admin:voucher-gomodo.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, 'KO', getException($exception));
            }


        }
        return abort(403);
    }

    /**
     *
     *disable voucher
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Request $request)
    {
        if (!$voucher = Voucher::find($request->id)) {
            return apiResponse(404, 'Voucher Not Found');
        }
        if ($voucher->status == 'Active') {
            $voucher->update(['status' => 0]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Disabled');
    }

    /**
     * enable voucher
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Request $request)
    {
        if (!$voucher = Voucher::find($request->id)) {
            return apiResponse(404, 'Voucher Not Found');
        }
        if ($voucher->status != 'Active') {
            $voucher->update(['status' => 1]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Enabled');
    }
}
