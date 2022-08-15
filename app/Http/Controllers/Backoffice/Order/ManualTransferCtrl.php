<?php

namespace App\Http\Controllers\Backoffice\Order;

use App\Enums\CustomerManualTransferStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrderExport;
use App\Traits\DiscordTrait;
use Carbon\Carbon;

class ManualTransferCtrl extends Controller
{
    use DiscordTrait;
    
    public function index($booking_type = 'all')
    {
        toastr();
        return viewKlhk('back-office.order.manual-transfer.index', ['new-backoffice.booking.manual-transfer.index', compact('booking_type')]);
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
        $model = Order::has('company')->with('company','payment')->whereHas('payment', function($q){
            $q->where('payment_gateway', 'like','%Manual Transfer%')->orWhere('payment_gateway', 'Cash On Delivery');
        })->select('tbl_order_header.*')
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
                if (isset($model->customer_manual_transfer->status) && $model->status == '0') {
                    return '<span class="badge badge-warning badge-pill p-2">'.$model->listManualTransfer()[$model->customer_manual_transfer->status].'</span>';
                } else {
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
                }
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
                    $html = '<a href="' . route('admin:master.transaction-manual.detail', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '" data-popup="tooltip" title="Lihat Detail"><i class="icon-eye"></i></a>';
                    $html .= '<form action="'.route('admin:master.transaction-manual.export').'" method="post">
                            '.csrf_field().'
                            <input type="hidden" name="range" value="'.$model->invoice_no.'" />
                            <input type="hidden" name="type" value="'.$booking_type[0].'" />
                            <button type="submit" class="btn btn-link m-0 p-0" data-popup="tooltip" title="Download">
                                <span class="icon-download" style="margin-bottom: 6px"></span>
                            </button>
                        </form>';
                } else {
                    $html = '<a href="' . route('admin:master.transaction-manual.detail', ['invoice' => $model->invoice_no]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
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
            return redirect()->route('admin:master.transaction-manual.index');
        }

        return viewKlhk(['back-office.order.manual-transfer.detail', $data], ['new-backoffice.booking.manual-transfer.detail', $data]);
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

    public function editManualTransfer(Request $request)
    {
        $rule = [
            'status' => 'required',
        ];

        $this->validate($request, $rule);

        $invoice_no = $request->segment(3);
        $order = Order::where('invoice_no', $invoice_no)
            ->whereHas('payment', function ($q) {
                $q->where('payment_gateway', 'Manual Transfer BCA');
            })->first();
        if (!$order) {
            return abort(404);
        }

        switch ($request->status) {
            case 'accept':
                $order->update(['status' => 1]);
                $order->payment->update(['status' => 'PAID']);
                $order->customer_manual_transfer->update([
                    'status' => CustomerManualTransferStatus::StatusAccept
                ]);
            break;
            case 'rejected_reupload':
                $order->payment->update(['expiry_date' => now()->addDay()->toDateTimeString()]);
                $order->customer_manual_transfer->update([
                    'status' => CustomerManualTransferStatus::StatusRejectReupload
                ]);
            break;
            case 'rejected':
                $order->update(['status' => 6]);
                $order->payment->update(['status' => 'CANCEL BY VENDOR']);
                $order->customer_manual_transfer->update([
                    'status' => CustomerManualTransferStatus::StatusReject
                ]);
            break;
        }
        
        // Send Mail to Customer
        $sendEMail = app('\App\Services\ProductService');
        $sendEMail->allMailCustomer($order->id_company, $invoice_no);

        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
        $newCompany = $order->company;
        $loc = \Stevebauman\Location\Facades\Location::get($ip);
        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
        $content = '**New Provider Confirmation to Customer Status :'.$order->listManualTransfer()[$request->status].' '. Carbon::now()->format('d M Y H:i:s').'**';
        $content .= '```';
        $content .= "Company Name    : ".$newCompany->company_name."\n";
        $content .= "Domain Gomodo   : ".$http.$newCompany->domain_memoria."\n";
        $content .= "Email Company   : ".$newCompany->email_company."\n";
        $content .= "Phone Number    : ".$newCompany->phone_company."\n";
        $content .= "Invoice Name    : ".$order->order_detail->product_name."\n";
        $content .= "Customer Name   : ".$order->customer_info->first_name."\n";
        $content .= "Customer Email  : ".$order->customer_info->email."\n";
        $content .= "Total Nominal   : ".format_priceID($order->total_amount)."\n";
        $content .= "Payment Method  : ".$order->payment->payment_gateway."\n";
        $content .= "Check Order     : ".$http.env('APP_URL').'/back-office/transaction-manual/'.$order->invoice_no.'/detail'."\n";
        if ($order->voucher):
            $content .= "Use Voucher     :  Yes\n";
            $content .= "Voucher Code    : ".$order->voucher_code."\n";
            $content .= "Voucher Amount  : ".format_priceID($order->voucher_amount)."\n";
            if ($order->voucher->by_gomodo == '1'):
                $content .= "Voucher By      :  Gomodo\n";
            else:
                $content .= "Voucher By      :  Provider\n";
            endif;
        endif;
        $content .= "IP Address      : ".$ip."\n";
        $content .= "City name       : ".$loc->cityName."\n";
        $content .= "Region Name     : ".$loc->regionName."\n";
        $content .= "Country Code    : ".$loc->countryCode."\n";
        $content .= '```';

        $this->sendDiscordNotification($content, 'transaction');

        return apiResponse(200, 'Ubah status berhasil');
    }
}
