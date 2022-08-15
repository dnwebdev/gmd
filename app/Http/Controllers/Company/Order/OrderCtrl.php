<?php

namespace App\Http\Controllers\Company\Order;

use App\Exports\TransactionExport;
use App\Models\Company;
use App\Models\GuideInformation;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OrderCtrl extends Controller
{
    /**
     * __construct
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return void
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }
        toastr();
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.order.newindex');
        }
        return view('dashboard.company.order.newindex');
    }

    /**
     * function load datatables order
     *
     * @param  mixed  $request
     *
     * @return void
     * @throws Exception
     */
    private function loadData($request)
    {
        $company = auth('web')->user()->company;
        $product_id = $request->query('product_id');
        $date = $request->query('date');
        $order = Order::where('id_company', $company->id_company)
            ->where('booking_type', 'online')
            ->with(['order_detail.product', 'customer_info'])
            ->when($product_id, function ($query, $product_id) {
                return $query->whereHas('order_detail', function ($q) use ($product_id) {
                    return $q->where('id_product', $product_id);
                });
            })
            ->when($date, function ($query, $date) {
                return $query->whereHas('order_detail', function ($q) use ($date) {
                    return $q->whereDate('schedule_date', Carbon::createFromFormat('d/m/Y', $date)->toDateString());
                });
            })
            ->orderBy('created_at', 'desc');
        return \DataTables::of($order)
            ->addIndexColumn()
            ->editColumn('invoice_no', function ($model) {
                if ($model->is_read == '1'){
                    return '<a href="'.route('company.order.edit',
                            ['invoice_no' => $model->invoice_no]).'">'.$model->invoice_no.'</a>';
                }
                return '<a href="'.route('company.order.edit',
                        ['invoice_no' => $model->invoice_no]).'">'.$model->invoice_no.' <sup class="text-warning">'.trans('dashboard_provider.unread').'</sup></a>';
            })
            ->editColumn('amount', function ($model) {
                return $model->order_detail->product->currency.' '.number_format($model->total_amount, 0);
            })
            ->editColumn('order_detail.product.product_name', function ($model) {
                return '<a href="'.route('company.product.edit',
                        ['id_product' => $model->order_detail->product->id_product]).'">'.$model->order_detail->product->product_name.'</a>';
            })
            ->editColumn('order_detail.schedule_date', function ($model) {
                return Carbon::parse($model->order_detail->schedule_date)->format('d M Y');
            })
            ->editColumn('status', function ($model) {
                if ($model->status == '1'){
                    if ($model->payment->status == 'PENDING'){
                        return 'Waiting for Settlement';
                    }
                    return $model->status_text;
                }
                if (isset($model->customer_manual_transfer->status) && $model->status == '0') {
                    return $model->listManualTransfer()[$model->customer_manual_transfer->status];
                } else{
                    return $model->status_text;
                }
            })
            ->addcolumn('action', function ($model) {
                if ($model->status == '0') {
                    return '<button class="btn btn-table btn-sm btn-danger btn-cancel-invoice px-2 py-1" data-id="'.$model->invoice_no.'">'.trans('order_provider.cancel').'</button>';
                }
            })
            ->rawColumns(['invoice_no', 'order_detail.product.product_name', 'action'])
            ->make(true);
    }

    /**
     * function save guide
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function saveGuide(Request $request)
    {
        $company = auth('web')->user()->company;
        $rules = [
            'guide_name' => 'required|max:100',
            'invoice_no' => [
                'required',
                Rule::exists('tbl_order_header', 'invoice_no')->where('id_company', $company->id_company)
            ],
            'guide_phone_number' => 'required|numeric|digits_between:6,20',
        ];

        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();
            $order = Order::find($request->input('invoice_no'));
            $guides = $order->guides()->create([
                'guide_name' => $request->input('guide_name'),
                'guide_phone_number' => $request->input('guide_phone_number')
            ]);
            $order->update(['has_sent_guide_email' => 0]);
            \DB::commit();

            return apiResponse(200, 'Guide saved', $guides);
        } catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, 'Something Wrong with your input', getException($exception));
        }
    }

    /**
     * Remove the data guide from the specified resource from storage.
     *
     * @param  mixed  $request
     *
     * @return void
     * @throws Exception
     */
    public function deleteGuide(Request $request)
    {
        $company = auth('web')->user()->company;
        if ($guide = GuideInformation::where('id', $request->id)->whereHas('order', function ($order) use ($company) {
            $order->where('id_company', $company->id_company);
        })->first()) {
            $guide->order->update(['has_sent_guide_email' => 0]);
            $guide->delete();
            return apiResponse(200, 'Guide Deleted');
        } else {
            return apiResponse(404, 'Guide not Found');
        }
    }

    /**
     * function send detail order email
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function sendDetailOrderEmail(Request $request)
    {
        $company = auth('web')->user()->company;
        if ($order = Order::where('invoice_no', $request->input('invoice_no'))->where('id_company',
            $company->id_company)->first()) {
            $utility = app('\App\Services\ProductService');
            $utility->allMailCustomer($company->id_company, $order->invoice_no);
            $utility->sendWACustomer( $order->invoice_no);
            $order->update(['has_sent_guide_email' => 1]);
            return apiResponse(200, 'Sent');
        }
        return apiResponse(404, 'not found');

    }

    public function exportData(Request $request)
    {
        $company = Company::find(auth('web')->user()->id_company);

        $range = explode(' - ', $request->get('range'));
        $tipe = null;
        if (checkRequestExists($request, 'type')) {
            $tipe = $request->get('type');
        }

        return (new TransactionExport($company, $tipe, $request->get('status'), $range[0], $range[1]))->download
        (Carbon::now()->toDateTimeString().'-'.$company->domain_memoria.'.xls');

    }

    public function cancelInvoice(Request $request)
    {
        $company = auth('web')->user()->company;
        if ($order = Order::where('invoice_no', $request->input('id'))->where('id_company',
            $company->id_company)->first()) {
            if ($order->status != '0') {
                return apiResponse(403, 'Wrong Status');
            }
            try {
                \DB::beginTransaction();
                $status = \Xendit::makeExpiredInvoice($order->invoice_no)->send();
                if (isset($status->error) && $status->error === false) {
                    $order->update(['status' => 6]);
                    if ($order->payment){
                        $order->payment->update(['status'=>'CANCELLED']);
                    }
                    \DB::commit();
                    $utility = app('\App\Services\ProductService');
                    $utility->allMailCustomer($company->id_company, $order->invoice_no);
                    $utility->sendWACustomer($order->invoice_no);
                    $utility->allMailProvider($company->id_company, $order->invoice_no);
                    $utility->sendWAProvider( $order->invoice_no);
                    \Log::info('WAProvider from'.OrderCtrl::class.' line 240');
                    return apiResponse(200, 'Ok');
                } else {
                    if (isset($status->code) && $status->code == "INVOICE_NOT_FOUND_ERROR") {
                        $order->update(['status' => 6]);
                        if ($order->payment){
                            $order->payment->update(['status'=>'CANCELLED']);
                        }
                        \DB::commit();
                        $utility = app('\App\Services\ProductService');
                        $utility->allMailCustomer($company->id_company, $order->invoice_no);
                        $utility->sendWACustomer($order->invoice_no);
                        $utility->allMailProvider($company->id_company, $order->invoice_no);
                        $utility->sendWAProvider( $order->invoice_no);
                        \Log::info('WAProvider from'.OrderCtrl::class.' line 254');
                        return apiResponse(200, 'Ok');
                    }
                }

            } catch (Exception $exception) {
                \DB::rollBack();

                return apiResponse(500, $exception->getMessage());
            }

        }
        return apiResponse(404, 'not found');
    }

}
