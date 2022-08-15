<?php

namespace App\Console\Commands;

use App\Models\UserOtp;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Console\Command;

class ExpiredOtpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:expired';

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
        UserOtp::where('created_at','<',Carbon::now()->subMinutes(10)->toDateTimeString())->delete();
    }
}
