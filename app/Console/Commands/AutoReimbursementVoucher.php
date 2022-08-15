<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoReimbursementVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:autoReimbursement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try{
            \DB::beginTransaction();
            \App\Models\Order::where('payment_list', '!=', 'Manual Transfer')->whereHas('payment', function ($q) {
                $q->where(['status' => 'PAID'])->where('payment_gateway','!=','Cash On Delivery');

            })
                ->whereHas('voucherGomodo')
                ->where('reimbursement',0)->update(['reimbursement'=>1]);
            \DB::commit();
            return 'OK';
        }catch (\Exception $exception){
            \DB::rollBack();
            return 'KO';
        }
    }
}
