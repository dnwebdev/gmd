<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('check:orderExpired')->everyMinute();
        $schedule->command('check:autoReimbursement')->weeklyOn(1, '00:01');
        $schedule->command('validate:voucher')->dailyAt('00:00');
        $schedule->command('check:orderMidtrans')->everyMinute();
        $schedule->command('kredivo:check')->everyMinute();
        $schedule->command('check:orderEwallet')->everyMinute();
        $schedule->command('weekly:bot')->weeklyOn(1, '00:01');
        $schedule->command('manualtransfer:check')->dailyAt('09.00');
        $schedule->command('manualtransfer:expired')->everyMinute();
        $schedule->command('otp:expired')->everyMinute();
        if ($this->app->environment() == 'production'):
            $schedule->command('analytic:get')->dailyAt('00:00');
        endif;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
