<?php

namespace App\Exports;


use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromView, ShouldAutoSize
{

    use Exportable;

    /**
     * @var string
     */
    private $startDate;
    /**
     * @var null
     */
    private $provider;
    /**
     * @var null
     */
    private $endDate;
    /**
     * @var null
     */
    private $status;
    /**
     * @var string
     */
    private $type;

    public function __construct($provider = null,$type=null, $status = null, $startDate = null, $endDate = null)
    {

        $this->provider = $provider;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->type = $type;
    }

    public function view(): View
    {
        $order = Order::query()->with(['company' => function ($company) {

        }, 'order_detail', 'payment','customer_info']);
        if ($this->provider) {
            $order->where('id_company', $this->provider->id_company);
        }
        if ($this->startDate) {
            $order->where('created_at', '>=', Carbon::parse($this->startDate)->startOfDay());
        }
        if ($this->endDate) {
            $order->where('created_at', '<=', Carbon::parse($this->endDate)->endOfDay());
        }
        if ($this->type !== null) {
            $order->where('booking_type','=' ,$this->type);
        }
        if ($this->status !== null) {
            $order->where('status','=' ,(int)$this->status);
        }
        return view('exports.invoices', [
            'invoices' => $order->orderBy('created_at','asc')->get(),
            'provider'=>$this->provider
        ]);
    }
}
