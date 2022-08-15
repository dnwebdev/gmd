<?php

namespace App\Exports;

use App\Models\Company;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProviderExport implements FromView, ShouldAutoSize
{

    use Exportable;

    private $type;
    /**
     * @var null
     */
    private $startDate;
    /**
     * @var null
     */
    private $endDate;
    /**
     * @var int
     */
    private $status;

    public function __construct($type, $startDate=null, $endDate=null, $status=1)
    {
        $this->type = $type;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function view(): View
    {


        switch ($this->type){
            case 'hasTransaction':
                $providers = DB::table('tbl_order_header as o')
                    ->select(DB::raw('c.company_name,c.domain_memoria,sum(total_amount-fee_credit_card) as gmv,count(invoice_no) as total_transaction'))
                    ->join('tbl_company as c','o.id_company','=','c.id_company')
                    ->groupBy('o.id_company');
                $providers->having('total_transaction','>',0);
                if ($this->startDate) {
                    $providers->where('o.created_at', '>=', Carbon::parse($this->startDate)->toDateTimeString());
                }
                if ($this->endDate) {
                    $providers->where('o.created_at', '=<', Carbon::parse($this->endDate)->toDateTimeString());
                }
                break;
            case 'hasSuccessfulTransaction':
                $providers = DB::table('tbl_order_header as o')
                    ->select(DB::raw('c.company_name,c.domain_memoria,sum(total_amount-fee_credit_card) as gmv,count(invoice_no) as total_transaction'))
                    ->join('tbl_company as c','o.id_company','=','c.id_company')
                    ->groupBy('o.id_company');
                $providers->where('c.status','1')->having('total_transaction','>',0);
                if ($this->startDate) {
                    $providers->where('o.created_at', '>=', Carbon::parse($this->startDate)->toDateTimeString());
                }
                if ($this->endDate) {
                    $providers->where('o.created_at', '<=', Carbon::parse($this->endDate)->toDateTimeString());
                }
                break;
            default:
                $providers = Company::with('agent');
        }

        $data['companies'] = $providers->get();
        $data['type'] = $this->type;

        return view('exports.providers',$data);
    }
}
