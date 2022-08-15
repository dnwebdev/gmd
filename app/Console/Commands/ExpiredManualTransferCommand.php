<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredManualTransferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manualtransfer:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Expired Manual Transfer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manualExpired = Order::where('status', 0)->where('booking_type', 'online')->where('payment_list', 'Manual Transfer')
            ->where('created_at', '<', now()->subDay(1)->toDateTimeString())
            ->whereHas('payment', function ($payment) {
                $payment->where('status', 'PENDING')->where('payment_gateway', 'Manual Transfer BCA');
            })
            ->whereDoesntHave('customer_manual_transfer', function ($manual) {
                $manual->where('status', 'need_confirmed');
            })->update(['status' => '7']);
            
        $manualOfflineExpired = Order::where('status', 0)->where('booking_type', 'offline')->where('payment_list', 'Manual Transfer')
            ->whereHas('payment', function ($payment) {
                $payment->where('status', 'PENDING')->where('payment_gateway', 'Manual Transfer BCA')
                    ->where('expiry_date', '<', now()->toDateTimeString());
            })
            ->whereDoesntHave('customer_manual_transfer', function ($manual) {
                $manual->where('status', 'need_confirmed');
            })->update(['status' => '7']);
        
        // ketika rejectreupload tidak di proses sampai expiry date abis maka status auto cancel by system
        // $manualRejectreupload = Order::where('status', 0)->where('booking_type', 'online')->where('payment_list', 'Manual Transfer')
        //     ->whereHas('payment', function ($payment) {
        //         $payment->where('status', 'PENDING')->where('payment_gateway', 'Manual Transfer BCA')
        //             ->where('expiry_date', '<', Carbon::now()->toDateTimeString());
        //     })
        //     ->whereHas('customer_manual_transfer', function ($manual) {
        //         $manual->where('status', 'rejected_reupload');
        //     })->update(['status' => '7']);
    }
}
