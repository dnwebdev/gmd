<?php

namespace App\Console\Commands;

use App\Models\OfflineOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BotWeeklyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:bot';

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
        if (env('APP_ENV') == 'production'):
            $targetProvidersThisYear = env('TARGET_PROVIDER', 4000);
            $targetRGMVThisYear = env('TARGET_GMV', 400000000000);
            $targetProductThisYear = env('TARGET_PRODUCT', 12000);
            $deadline = Carbon::now()->endOfYear();
            $today = Carbon::now();
            $remaining = $today->diffInMonths($deadline);


            $end = Carbon::now()->toDateTimeString();
            $start = Carbon::now()->subWeek(1)->toDateTimeString();
            $result[] = 'start ' . $start . ' - ' . $end;
            $result[] = '==========================================================================================';
            $result[] = 'TRANSACTIONS';
            $orderOnline = \App\Models\Order::where('booking_type', 'online')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end);
            $totalTransaction = $orderOnline->count();
            $totalVolumeTransaction = $orderOnline->sum('total_amount');
            $result[] = $totalTransaction . ' Total Transactions, with ' . format_priceID($totalVolumeTransaction);
            $orderOnline = \App\Models\Order::where('booking_type', 'online')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)->where('status', 1);
            $totalTransaction = $orderOnline->count();
            $totalVolumeTransaction = $orderOnline->sum('total_amount');
            $result[] = $totalTransaction . ' Paid Transactions, with ' . format_priceID($totalVolumeTransaction);
            $orderOnline = \App\Models\Order::where('booking_type', 'online')->where('status', 1)->where('created_at', '>', '2019-03-01 00:00:00')->whereHas('payment');
            $totalTransaction = $orderOnline->count();
            $totalVolumeTransaction = $orderOnline->sum('total_amount');
            $result[] = $totalTransaction . ' Grand Total Paid Transactions as of today';
            $result[] = format_priceID($totalVolumeTransaction) . ' Grand Total Paid Transactions volume as today';
            $result[] = '==========================================================================================';
            $result[] = 'INVOICES';
            $orderOffline = \App\Models\Order::where('booking_type', 'offline')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end);
            $totalOffline = $orderOffline->count();
            $totalVolumeOffline = $orderOffline->sum('total_amount');
            $result[] = $totalOffline . ' Total Invoices, with ' . format_priceID($totalVolumeOffline);
            $orderOffline = \App\Models\Order::where('booking_type', 'offline')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)->where('status', 1);
            $totalOffline = $orderOffline->count();
            $totalVolumeOffline = $orderOffline->sum('total_amount');
            $result[] = $totalOffline . ' Paid Invoices, with ' . format_priceID($totalVolumeOffline);
            $orderOffline = \App\Models\Order::where('booking_type', 'offline')->where('status', 1);
            $totalOffline = $orderOffline->count();
            $totalVolumeOffline = $orderOffline->sum('total_amount');
            $result[] = $totalOffline . ' Grand Total Paid Invoices as of today';
            $result[] = format_priceID($totalVolumeOffline) . ' Grand Total Paid Invoices volume as of today';
            $result[] = '==========================================================================================';
            $result[] = 'Total GMV as of Today';
            $result[] = $totalTransaction + $totalOffline . ' Grand total successful payments (invoices + transactions)';
            $result[] = format_priceID($totalVolumeTransaction + $totalVolumeOffline) . ' Grand total successful payments volume (invoices + transactions)';
            $result[] = '==========================================================================================';
            $result[] = 'Deadline                      : ' . $deadline;
            $result[] = 'Value to achieve              : ' . format_priceID($targetRGMVThisYear);
            $result[] = 'Month(s) remaining            : ' . $remaining;
            $result[] = 'Complete (%)                  : ' . number_format(((($totalVolumeOffline + $totalVolumeTransaction) / $targetRGMVThisYear) * 100), 2) . " %";
            $result[] = 'Average to achieve each month : ' . format_priceID((int)round(($targetRGMVThisYear - ($totalVolumeOffline + $totalVolumeTransaction)) / $remaining));
            $content1 = sprintf('%s', implode("\n", $result));

            $providerOnboard = \App\Models\Company::where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)->count();
            $totalProvider = \App\Models\Company::count();
            $result = [];
            $result[] = 'start ' . $start . ' - ' . $end;
            $result[] = '================================================';
            $result[] = 'PROVIDER';
            $result[] = $providerOnboard . ' Provider On board this week';
            $result[] = $totalProvider . ' Provider On board as of today';
            $result[] = '================================================';
            $result[] = 'Deadline                      : ' . $deadline;
            $result[] = 'Value to achieve              : ' . $targetProvidersThisYear;
            $result[] = 'Month(s) remaining            : ' . $remaining;
            $result[] = 'Complete (%)                  : ' . number_format(((($totalProvider) / $targetProvidersThisYear) * 100), 2) . " %";
            $result[] = 'Average to achieve each month : ' . (int)round(($targetProvidersThisYear - ($totalProvider)) / $remaining);
            $content2 = sprintf('%s', implode("\n", $result));
            $product = \App\Models\Product::where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)->count();
            $totalProduct = \App\Models\Product::count();
            $result = [];
            $result[] = 'start ' . $start . ' - ' . $end;
            $result[] = '================================================';
            $result[] = 'Product';
            $result[] = $product . ' Product created this week';
            $result[] = $totalProduct . ' Product created as of today';
            $result[] = '================================================';
            $result[] = 'Deadline                      : ' . $deadline;
            $result[] = 'Value to achieve              : ' . $targetProductThisYear;
            $result[] = 'Month(s) remaining            : ' . $remaining;
            $result[] = 'Complete (%)                  : ' . number_format(((($totalProduct) / $targetProductThisYear) * 100), 2) . " %";
            $result[] = 'Average to achieve each month : ' . (int)round(($targetProductThisYear - ($totalProduct)) / $remaining);
            $content3 = sprintf('%s', implode("\n", $result));
            skema($content1);
            skema($content2);
            skema($content3);
            $url = 'https://discordapp.com/api/webhooks/683717194814586892/L3HNqz6nck6ebtyK4bj0wGWEXIxS5sH0iHEKWxKqqshoE8lEVOkMRUFmw5ns3Tc6iJ8W';
            $headers = array(
                'Content-Type:application/json'
            );
            $method = "POST";
            $data['content'] = $content1;
            $data = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_exec($ch);
            curl_close($ch);
            $url = 'https://discordapp.com/api/webhooks/683717384716025928/ffqREtBwR0kj6_UZ8lqkquDnaeK_byLtNJddex7Q7dfYdCZJ_P2rKEj--ylLdjYPnCFC';
            $data = [];
            $data['content'] = $content2;
            $data = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_exec($ch);
            curl_close($ch);
            $url = 'https://discordapp.com/api/webhooks/683717664988070032/LNwoGQiRz-sISW6dXoWPS8suXYHw8vL28oJYii50Hq4INBzD_ot04quhD9qEGuQvmOrg';
            $data = [];
            $data['content'] = $content3;
            $data = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_exec($ch);
            curl_close($ch);
            $result = [];
            $result[] = '================================================';
            $result[] = "Distribution Transaction";
            $result[] = '================================================';
            $result[] = format_priceID(OfflineOrder::where('channel','distribution')->sum('amount'));
            $content4 = sprintf('%s', implode("\n", $result));

            $url = 'https://discordapp.com/api/webhooks/683717664988070032/LNwoGQiRz-sISW6dXoWPS8suXYHw8vL28oJYii50Hq4INBzD_ot04quhD9qEGuQvmOrg';
            $data = [];
            $data['content'] = $content4;
            $data = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_exec($ch);
            curl_close($ch);
        endif;
    }
}
