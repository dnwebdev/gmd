<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckManualTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manualtransfer:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Manual Transfer';

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
        $orders = Order::where('status', 0)->where('payment_list', 'Manual Transfer')
            ->whereHas('payment', function ($payment) {
                $payment->where('status', 'PENDING')->where('payment_gateway', 'Manual Transfer BCA');
            })
            ->whereHas('customer_manual_transfer', function ($manual) {
                $manual->where('status', 'need_confirmed')->orWhere('status', 'customer_reupload');
            })
            ->where('booking_type', 'online');
            
        $checkToCs = $orders->where('created_at', '<=', Carbon::now()->subDay(3)->toDateTimeString())->get();
        $checkToProvider = $orders->where('created_at', '<=', Carbon::now()->subDay(1)->toDateTimeString())->get();
        foreach ($checkToCs as $key => $data) {
            $order = Order::where('invoice_no', $data->invoice_no)->first();
            $newCompany = $order->company;
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : \request()->ip();
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**Hai @Client Success Squad please follow up your provider ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name       : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo      : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Email Company      : " . optional($newCompany)->email_company . "\n";
            $content .= "Phone Number       : " . optional($newCompany)->phone_company . "\n";
            $content .= "Invoice Name       : " . $order->order_detail->product_name . "\n";
            $content .= "Customer Name      : " . $order->customer_info->first_name . "\n";
            $content .= "Customer Email     : " . $order->customer_info->email . "\n";
            $content .= "Total Nominal      : " . format_priceID($order->total_amount) . "\n";
            $content .= "Payment Method     : " . $order->payment->payment_gateway . "\n";
            $content .= "Check Detail Order : " . $http . env('APP_URL') . '/back-office/transaction/' . $order->invoice_no . '/detail' . "\n";
            if ($order->voucher) :
                $content .= "Use Voucher        :  Yes\n";
                $content .= "Voucher Code       : " . $order->voucher_code . "\n";
                $content .= "Voucher Amount     : " . format_priceID($order->voucher_amount) . "\n";
                if ($order->voucher->by_gomodo == '1') :
                    $content .= "Voucher By      :  Gomodo\n";
                else :
                    $content .= "Voucher By      :  Provider\n";
                endif;
            endif;
            $content .= "IP Address      : " . $ip . "\n";
            $content .= "City name       : " . $loc->cityName . "\n";
            $content .= "Region Name     : " . $loc->regionName . "\n";
            $content .= "Country Code    : " . $loc->countryCode . "\n";
            $content .= '```';
            $this->sendDiscordNotification(sprintf('%s', $content), 'transaction');
        }

        foreach ($checkToProvider as $key => $data) {
            $order = Order::where('invoice_no', $data->invoice_no)->first();
            $company = $order->company;
            $dataEmail = [
                'order' => $order,
                'company' => $company
            ];

            $subject = 'Reminder : Awaiting Transfer Confirmation #' . $order->invoice_no;
            $template = view('mail.manual-transfer.reminder-to-provider', $dataEmail)->render();

            dispatch(new SendEmail($subject, $company->email_company, $template));
        }
    }
}
