<?php

namespace App\Exports;

use App\Models\Company;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Order;

class OrderExport implements FromView, ShouldAutoSize
{

    use Exportable;

    private $_type;

    private $_id = null;

    private $_start_date = null;

    private $_end_date = null;

    private $_status = 'all';

    public function __construct($type, $range, $status)
    {
        $this->_type = $type;

        $ex = explode(' - ', $range, 2);
        
        if (count($ex) < 2) {
            $this->_id = $range;
        } else {
            $this->_start_date = Carbon::parse($ex[0]);
            $this->_end_date = Carbon::parse($ex[1]);
        }

        $this->_status = $status;


    }

    public function view(): View
    {
        $status = $this->_status;
        $id = $this->_id;
        $start_date = $this->_start_date;
        $end_date = $this->_end_date;

        $orders = Order::has('company')
            ->with('company', 'payment')
            ->where([
                ['created_at','>=','2019-03-01 00:00:00'],
                'booking_type' => $this->_type
            ])
            ->whereHas('company', function ($query) {
                return $query->where('is_klhk', request()->is_klhk);
            })
            ->when($status, function ($query, $status) {
                return $query->wherePaid();
            })
            ->when($id, function ($query, $id) {
                return $query->where('invoice_no', $id);
            }, function ($query) use ($start_date, $end_date) {
                return $query->whereHas('order_detail', function ($query) use ($start_date, $end_date) {
                    return $query->where('schedule_date', '>=', $start_date)
                        ->where('schedule_date', '<=', $end_date);
                });
            })
            ->get();

        return view('exports.orders', compact('orders'));
    }
}
