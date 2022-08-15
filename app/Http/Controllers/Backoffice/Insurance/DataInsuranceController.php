<?php

namespace App\Http\Controllers\Backoffice\Insurance;

use App\Exports\InsuranceExport;
use App\Models\AdditionalOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrderExport;
use Carbon\Carbon;

class DataInsuranceController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.insurance.data');
//        return viewKlhk('back-office.order.index', ['new-backoffice.booking.index', compact('booking_type')]);
    }

    public function loadData()
    {
        $is_klhk = request()->is_klhk;
        $booking_type = 'online';
        $model = AdditionalOrder::whereHas('order', function ($o){
            $o->where('status',1)->where('booking_type', 'online');
        })->where('type','insurance')->get();
        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('created_at', function ($model){
                return $model->order->created_at;
            })
            ->editColumn('schedule_date', function ($model){
                return Carbon::parse($model->order->order_detail->schedule_date)->format('d M Y');
            })
            ->editColumn('product_insurance', function ($model){
                return format_priceID($model->total);
            })
            ->editColumn('company_name', function ($model){
                return $model->order->company->company_name;
            })
//            ->editColumn('status', function ($model){
//                switch ($model->status){
//                    case 0:
//                        $class = request()->is_klhk ? 'badge badge-warning badge-pill p-2' : 'badge badge-warning badge-pill pl-3 pr-3 pt-2 pb-2';
//                        break;
//                    case 1:
//                        $class = request()->is_klhk ? 'badge badge-success badge-pill p-2' : 'badge badge-success badge-pill pl-3 pr-3 pt-2 pb-2';
//                        break;
//                    case 7:
//                        $class = request()->is_klhk ? 'badge badge-danger badge-pill p-2' : 'badge badge-danger badge-pill pl-3 pr-3 pt-2 pb-2';
//                        break;
//                    default:
//                        $class = 'badge badge-secondary badge-pill p-2';
//                        break;
//                }
//                return '<span class="'.$class.'">'.$model->status_text.'</span>';
//            })
            ->addColumn('action', function ($model) use ($booking_type) {
                if (request()->is_klhk) {
                    $html = '<a href="' . route('admin:master.transaction.detail', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '" data-popup="tooltip" title="Lihat Detail"><i class="icon-eye"></i></a>';
                    $html .= '<form action="'.route('admin:master.transaction.export').'" method="post">
                            '.csrf_field().'
                            <input type="hidden" name="range" value="'.$model->invoice_no.'" />
                            <input type="hidden" name="type" value="'.$booking_type.'" />
                            <button type="submit" class="btn btn-link m-0 p-0" data-popup="tooltip" title="Download">
                                <span class="icon-download" style="margin-bottom: 6px"></span>
                            </button>
                        </form>';
                } else {
                    $html = '<a href="' . route('admin:insurance.data-customer.detail-insurance', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
//                    $html = '<button data-id="' . $model->invoice_no . '" data-toggle="modal"
//                                    data-target=".modal-detail" class="btn btn-outline-info btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" id="modal-data"><i class="fa flaticon-visible"></i></button>';
                    $html .= '<form action="'.route('admin:insurance.data-customer.export').'" method="post">
                            '.csrf_field().'
                            <input type="hidden" name="range" value="'.$model->invoice_no.'" />
                            <input type="hidden" name="type" value="online" />
                            <button type="submit" class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" data-popup="tooltip" title="Download">
                                <i class="fa flaticon-download"></i>
                                <span class="icon-download" style="margin-bottom: 6px"></span>
                            </button>
                        </form>';
                }
                return $html;
            })->make(true);
    }

    public function detail($invoice)
    {
        $detailCustomer = AdditionalOrder::with('order')->where('invoice_no', $invoice)->first();
        return view('back-office.insurance.detail', compact('detailCustomer'));
    }

    public function export(Request $request)
    {
        $file_name = 'Transaksi with Insurance';
        $range = $request->input('range');

        $ex = explode(' - ', $range, 2);

        if (count($ex) < 2) {
            $file_name .= ' - '.$range;
        } else {
            $file_name .= ' - ' . implode(' - ', array_map(function ($date) {
                return Carbon::parse($date)->format('d M Y');
            }, $ex));
        }

        return (new InsuranceExport('online', $request->input('range', null), 1))
            ->download($file_name.'.xls');
    }
}
