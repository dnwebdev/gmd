<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\DiscordTrait;
use Carbon\Carbon;

class KredivoPayment extends Command
{
    use DiscordTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kredivo:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kredivo Payment Check';

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
        // Check order yang sudah saatnya settlement
        $orders = Order::where('status', 1)
            ->where('created_at', '<=', now()->subDays(3)->toDateTimeString())
            ->whereHas('payment', function ($query) {
                return $query->where('status', 'PENDING')
                    ->where('payment_gateway', 'Xendit Kredivo');
            })
            ->get();

        foreach ($orders as $order) {
            $order->payment()->update([
                'status'        => 'PAID',
                'settlement_on' => now(),
                'pay_at'        => now()
            ]);

            $product_service = app('\App\Services\ProductService');
            $product_service->allMailProvider($order->id_company, $order->invoice_no);
            $product_service->sendWAProvider($order->invoice_no);
            \Log::info('WAProvider from'.KredivoPayment::class.' line 64');

            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : request()->ip();
            $newCompany = $order->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New SETTLED Online Booking '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name    : ".$newCompany->company_name."\n";
            $content .= "Domain Gomodo   : ".$http.$newCompany->domain_memoria."\n";
            $content .= "Email Company   : ".$newCompany->email_company."\n";
            $content .= "Phone Number    : ".$newCompany->phone_company."\n";
            $content .= "Invoice Name    : ".$order->order_detail->product_name."\n";
            $content .= "Customer Name   : ".$order->customer_info->first_name."\n";
            $content .= "Customer Email  : ".$order->customer_info->email."\n";
            $content .= "Total Nominal   : ".format_priceID($order->total_amount)."\n";
            $content .= "Payment Method  : ".$order->payment->payment_gateway."\n";
            if ($order->voucher):
                $content .= "Use Voucher     :  Yes\n";
                $content .= "Voucher Code    : ".$order->voucher_code."\n";
                $content .= "Voucher Amount  : ".format_priceID($order->voucher_amount)."\n";
                if ($order->voucher->by_gomodo == '1'):
                    $content .= "Voucher By      :  Gomodo\n";
                else:
                    $content .= "Voucher By      :  Provider\n";
                endif;
            endif;
            $content .= "IP Address      : ".$ip."\n";
            $content .= "City name       : ".$loc->cityName."\n";
            $content .= "Region Name     : ".$loc->regionName."\n";
            $content .= "Country Code    : ".$loc->countryCode."\n";
            $content .= '```';

            $this->sendDiscordNotification($content, 'transaction');
        }

        // Check order yang expired
        $expiredKredivo = Order::where('status', 0)
            ->whereHas('payment', function ($query) {
                return $query->where('expiry_date', '<', now()->toDateTimeString())
                    ->where('payment_gateway', 'Xendit Kredivo')
                    ->where('status', 'PENDING');
            })
            ->update(['status' => 7]);

        // check user yang perlu di ingatkan kembali invoicenya
        $reminderKredivo = Payment::has('kredivo')
            ->where('status', 'INCOMPLETE')
            ->whereHas('kredivo', function ($query) {
                return $query->where('email_reminder_sent', 0);
            })
            ->where('created_at', '<=', now()->subMinutes(10))
            ->get();
        foreach ($reminderKredivo as $reminder) {
            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer($reminder->order->id_company, $reminder->invoice_no);
            $product_service->sendWACustomer( $reminder->invoice_no);
            $reminder->kredivo()->update(['email_reminder_sent' => 1]);
            sleep(1);
        }
    }
}
