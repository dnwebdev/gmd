<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;

class AutoPromoCodeValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:voucher';

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
        $max = 3;
        $vouchers = \App\Models\Voucher::where('by_gomodo', 1)
            ->where('status', 1)
            ->get();
        foreach ($vouchers as $voucher) {
            $now = \Carbon\Carbon::now();
            $diff = \Carbon\Carbon::now()->diffInMonths($voucher->created_at);
            if ((\Carbon\Carbon::now()->toDateTimeString() > $voucher->created_at) && ($diff < $max)) {
                echo `Now : ` . $now->toDateString() . `  ` . `  Valid : ` . $voucher->valid_end_date;


                if (\Carbon\Carbon::now()->toDateString() > $voucher->valid_end_date) {
                    $voucher->update([
                        'start_date' => \Carbon\Carbon::parse($voucher->start_date)->addMonth(1)->toDateString(),
                        'end_date' => \Carbon\Carbon::parse($voucher->end_date)->addMonth(1)->toDateString(),
                        'valid_start_date' => \Carbon\Carbon::parse($voucher->valid_start_date)->addMonth(1)->toDateString(),
                        'valid_end_date' => \Carbon\Carbon::parse($voucher->valid_end_date)->addMonth(1)->toDateString()
                    ]);
                } else {
                    echo "Masih Berlaku";
                }

            } else {
                echo "Tidak berlaku";
            }
        }
    }
}
