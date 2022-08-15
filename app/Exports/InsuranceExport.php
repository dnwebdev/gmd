<?php

namespace App\Exports;

use App\Models\AdditionalOrder;
use App\Models\Company;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Order;

class InsuranceExport implements FromView, ShouldAutoSize
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
//
        $additional = AdditionalOrder::whereHas('order', function ($o){
            $o->where('status',1)->where('booking_type', 'online');
        })->where('type','insurance')->when($id, function ($query, $id) {
                return $query->where('invoice_no', $id);
            })->when($start_date, function ($q, $start_date) use($end_date){
            $q->where('created_at', '>=', Carbon::parse($start_date)->startOfDay())
                ->where('created_at', '<=', Carbon::parse($end_date)->startOfDay())->get();
        })->get();

        return view('exports.insurance', compact('additional'));
    }
}
