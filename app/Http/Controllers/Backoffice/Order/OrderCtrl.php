<?php

namespace App\Http\Controllers\Backoffice\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrderExport;
use Carbon\Carbon;

class OrderCtrl extends Controller
{
    public function index($booking_type = 'all')
    {
        toastr();
        return viewKlhk('back-office.order.index', ['new-backoffice.booking.index', compact('booking_type')]);
    }

    public function loadData($booking_type = 'all')
    {
        $is_klhk = request()->is_klhk;

        switch ($booking_type) {
            case 'online':
                $booking_type = ['online'];
                break;
            case 'offline':
                $booking_type = ['offline'];
                break;
            
            default:
                $booking_type = ['online', 'offline'];
                break;
        }
        $model = Order::has('company')->with('company','payment')->where('payment_list', '!=', 'Manual Transfer')->whereHas('payment', function($q){
                $q->orWhere('payment_gateway', '!=', 'Cash On Delivery');
            })->where('tbl_order_header.created_at','>=','2019-03-01 00:00:00')->select('tbl_order_header.*')
            ->whereIn('booking_type', $booking_type)
            ->when($is_klhk, function ($query, $is_klhk) {
                return $query->whereHas('company', function ($query) {
                    return $query->where('is_klhk', request()->is_klhk);
                });
            });
        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('total_amount', function ($model){
                return format_priceID($model->total_amount);
            })
            ->editColumn('status', function ($model){
                switch ($model->status){
                    case 0:
                        $class = request()->is_klhk ? 'badge badge-warning badge-pill p-2' : 'badge badge-warning badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    case 1:
                        $class = request()->is_klhk ? 'badge badge-success badge-pill p-2' : 'badge badge-success badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    case 7:
                        $class = request()->is_klhk ? 'badge badge-danger badge-pill p-2' : 'badge badge-danger badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    default:
                        $class = 'badge badge-secondary badge-pill p-2';
                        break;
                }
                return '<span class="'.$class.'">'.$model->status_text.'</span>';
            })

            ->addColumn('paymentStatus', function ($model){
                switch (optional($model->payment)->status){
                    case 'PENDING':
                        if ($model->status =='7'){
                            return '<span class="badge badge-danger badge-pill pl-3 pr-3 pt-2 pb-2">FAILED</span>';
                        }
                        return '<span class="badge badge-warning badge-pill pl-3 pr-3 pt-2 pb-2">PENDING</span>';
                        break;
                    case 'PAID':
                        return '<span class="badge badge-success badge-pill pl-3 pr-3 pt-2 pb-2">PAID</span>';
                        break;
                    default:
                        if (optional($model->payment)->payment_gateway =='Cash On Delivery'){
                            if ($model->status == '1'){
                                return '<span class="badge badge-success badge-pill pl-3 pr-3 pt-2 pb-2">PAID</span>';
                            }
                        }
                        return '<span class="badge badge-danger badge-pill pl-3 pr-3 pt-2 pb-2">NO STATUS</span>';
                        break;

                }
            })
            ->addColumn('action', function ($model) use ($booking_type) {
                if (request()->is_klhk) {
                    $html = '<a href="' . route('admin:master.transaction.detail', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '" data-popup="tooltip" title="Lihat Detail"><i class="icon-eye"></i></a>';
                    $html .= '<form action="'.route('admin:master.transaction.export').'" method="post">
                            '.csrf_field().'
                            <input type="hidden" name="range" value="'.$model->invoice_no.'" />
                            <input type="hidden" name="type" value="'.$booking_type[0].'" />
                            <button type="submit" class="btn btn-link m-0 p-0" data-popup="tooltip" title="Download">
                                <span class="icon-download" style="margin-bottom: 6px"></span>
                            </button>
                        </form>';
                } else {
                    $html = '<a href="' . route('admin:master.transaction.detail', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
                }

                return $html;
            })
            ->rawColumns(['action','status','paymentStatus'])
            ->make(true);
    }

    public function detail($invoice)
    {
        if (!$data['order'] = Order::with('order_detail')->find($invoice)){
            msg('Order Not Found', 2);
            return redirect()->route('admin:master.transaction.index');
        }

        return viewKlhk(['back-office.order.detail', $data], ['new-backoffice.booking.detail', $data]);
    }

    public function export(Request $request)
    {
        $type = [
            'online'    => 'Online',
            'offline'   => 'di Lokasi'
        ];

        $file_name = 'Transaksi '.$type[$request->input('type', 'online')];
        $range = $request->input('range');

        $ex = explode(' - ', $range, 2);
        
        if (count($ex) < 2) {
            $file_name .= ' - '.$range;
        } else {
            $file_name .= ' - ' . implode(' - ', array_map(function ($date) {
                return Carbon::parse($date)->format('d M Y');
            }, $ex));
        }

        return (new OrderExport($request->input('type', 'online'), $request->input('range', null), $request->input('status')))
            ->download($file_name.'.xls');
    }
}
