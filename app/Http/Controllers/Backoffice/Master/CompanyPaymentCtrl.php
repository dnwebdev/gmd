<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\Company;
use App\Models\CompanyPayment;
use App\Models\ListPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyPaymentCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.master.company-payment.index');
    }

    public function loadData()
    {
        $models = CompanyPayment::all();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('company', function ($model) {
                return $model->company->company_name;
            })
            ->addColumn('listpayment', function ($model) {
                return $model->paymentLists->name_payment;
            })
            ->addColumn('action', function ($model) {
                if ($model->charge_to == 1) {
                    $html = ' <button data-id="'.$model->payment_id.'" data-company_id="'.$model->company_id.'" class="btn-nonactive btn btn-outline-success  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-up"></i> Customer';
                    $html .= '</button>';
                } else {
                    $html = ' <button data-id="'.$model->payment_id.'" data-company_id="'.$model->company_id.'" class="btn-active btn btn-outline-danger  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-down"></i> Provider';
                    $html .= '</button>';
                }
                return $html;
            })->make(true);
    }

    public function searchProvider(Request $request)
    {

        $q = $request->input('q');
        $result = Company::select('id_company', 'company_name', 'domain_memoria', 'logo')
            ->where('company_name', 'like', '%'.$q.'%')
            ->orWhere('domain_memoria', 'like', '%'.$q.'%')
            ->orWhere('email_company', 'like', '%'.$q.'%');

        $data = [];
        foreach ($result->paginate(10) as $item) {

            $data[] = [
                'id' => $item->id_company,
                'text' => $item->company_name,
                'logo' => \File::exists(asset('uploads/company_logo/'.$item->logo)) ? asset('uploads/company_logo/'.$item->logo) : \File::exists(asset($item->logo)) ? asset($item->logo) : asset('img/no-product-image.png'),
                'domain_memoria' => $item->domain_memoria
            ];
        }
        return response()->json([
            'incomplete_results' => false,
            'items' => $data,
            'total_count' => $result->count()
        ]);
    }

    public function create()
    {
        $company = Company::all();
        $listPayment = ListPayment::whereStatus(1)->get();
        return view('back-office.page.master.company-payment.create',
            compact(
                'company', 'listPayment'
            ));
    }


    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $rules = [
            'id_company' => 'required',
            'listpayment' => 'required',
        ];

        $this->validate($request, $rules);

        try {
            \DB::beginTransaction();
            $data = new CompanyPayment();
            $data->company_id = $request->id_company;
            $data->payment_id = $request->listpayment;
            $data->charge_to = $request->charge_to;
            $data->save();
            \DB::commit();
            return apiResponse(200, 'Company Payment Success',
                ['redirect' => route('admin:master.company-payment.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $list = CompanyPayment::find($id);
        $company = $list->company()->pluck('company_name', 'id_company');
        $listPayment = ListPayment::whereStatus(1)->get();
        return view('back-office.page.master.company-payment.edit', compact('company', 'list', 'listPayment'));
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
        $rules = [
            'id_company' => 'required',
            'listpayment' => 'required',
        ];

        $this->validate($request, $rules);

        try {
            \DB::beginTransaction();
            $list = CompanyPayment::find($id);
            $list->company_id = $request->id_company;
            $list->payment_id = $request->listpayment;
            $list->charge_to = $request->charge_to;
            $list->save();

            \DB::commit();
            return apiResponse(200, 'Company Payment Success',
                ['redirect' => route('admin:master.company-payment.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function active(Request $request)
    {
        if (!$list = CompanyPayment::where('payment_id', $request->id)->where('company_id', $request->company_id)->first()) {
            return apiResponse(404, 'Company Payment not found');
        }
        if ($list->charge_to != 1) {
            $company = Company::find($request->company_id);
            $cc = ListPayment::find($request->id);
            $company->payments()->updateExistingPivot($cc->id,['charge_to' => 1]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Active');
    }

    public function nonactive(Request $request)
    {
        if (!$list = CompanyPayment::where('payment_id', $request->id)->where('company_id', $request->company_id)->first()) {
            return apiResponse(404, 'Company Payment Found');
        }
        if ($list->charge_to != 0) {
            $company = Company::find($request->company_id);
            $cc = ListPayment::find($request->id);
            $company->payments()->updateExistingPivot($cc->id,['charge_to' => 0]);
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
        $del = CompanyPayment::find($request->id);
        $del->delete();
        msg('Company Payment Deleted!');
        return redirect()->back();
    }

}
