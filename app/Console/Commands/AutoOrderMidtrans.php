<?php

namespace App\Console\Commands;

use App\Models\CashBackVoucher;
use App\Models\Company;
use App\Models\JournalGXP;
use App\Models\ListPayment;
use App\Models\Order;
use App\Traits\DiscordTrait;
use App\Traits\MidtransTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoOrderMidtrans extends Command
{
    use DiscordTrait;
    use MidtransTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:orderMidtrans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Order Payment Midtrans';

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
        $orders = Order::where('status', 1)->where('payment_list', 'Midtrans Payment')
            ->whereHas('payment', function ($payment) {
            $payment->where('status', 'PENDING')->where('payment_gateway', 'like', 'Midtrans%')
            ->where('settlement_on', '<=', Carbon::now()->toDateString());
        })->get();
        
        foreach ($orders as $order) {
            try {
                \DB::beginTransaction();
                if ($order->payment && $order->payment->payment_gateway != 'Cash On Delivery') {
                    // $response = json_encode($this->MidtransApi('get-order', $data, $order->invoice_no));
                    // $res = json_encode($order->payment->response_midtrans, true);
                    // $response = json_decode($res);
                    $response = \Gomodo\Midtrans\MidTrans::getorder($order->invoice_no)->send();
                    if (isset($response->data->status_code)) {
                        if ($response->data->status_code === '200' && $response->data->transaction_status == 'settlement') {
                            $order->payment->update([
                                'status' => 'PAID',
                                'pay_at' => now()
                            ]);
                            $journal = app('App\Services\JournalService');
                            $journal->add([
                                'id_company' => $order->id_company,
                                'journal_code' => $order->invoice_no,
                                'journal_type' => 100,
                                'description' => 'Order Product : '.$order->order_detail->product_name,
                                'currency' => $order->order_detail->currency,
                                'rate' => $order->order_detail->rate,
                                'amount' => ($order->total_amount - $order->fee_credit_card) - $order->fee,
                            ]);
                            $company = Company::find($order->id_company);
                            $activePeriodeInMonth = 3;
                            $procentase = 5 / 100;
                            $upTo = 1000000;
                            $nominalCashbackVoucher = 300000;
                            $responeText = '';
                            $startdate = $company->created_at;

                            if ($company->created_at < '2019-04-01 00:00:00') {
                                $startdate = Carbon::parse('2019-04-01 00:00:00');
                            }

                            if ($startdate >= Carbon::now()->subMonths($activePeriodeInMonth)) {

                                $diff = $startdate->diffInMonths(Carbon::now());

                                switch ($diff) {
                                    case 0:
                                        $add1 = Carbon::parse($startdate)->addMonth(1)->toDateTimeString();
                                        $totalNominalJournal = $company->gpx_journals()->where('type',
                                            'in')->where('gxp_type', 'incentive')->where('created_at', '>',
                                            $startdate->toDateTimeString())->where('created_at', '<=',
                                            $add1)->sum('nominal');

                                        $countOrder = $company->order()
                                            ->wherehas('payment', function ($payment) {
                                                $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                            })
                                            ->where('created_at', '>',
                                                $startdate->toDateTimeString())->where('created_at', '<=', $add1)
                                            ->whereIn('status', [1, 2, 3, 4])
                                            ->count();

                                        if ($totalNominalJournal < $upTo) {
                                            $nominal = floor($procentase * (($order->total_amount - $order->fee_credit_card) - $order->fee));
                                            if ($totalNominalJournal + $nominal > $upTo) {
                                                $finalNominal = $upTo - $totalNominalJournal;

                                            } else {
                                                $finalNominal = $nominal;
                                            }
                                            $journalGxp = JournalGXP::create([
                                                'description' => 'Insentif '.$procentase.'% month'.($diff + 1),
                                                'nominal' => $finalNominal,
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'id_company' => $order->id_company,
                                                'gxp_type' => 'incentive',
                                                'type' => 'in'
                                            ]);

                                        }
                                        $responeText .= "Count order $countOrder \n";
                                        if ($countOrder === 1) {
                                            $responeText .= "Check Pertama \n";
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (10 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 1st successful transaction in the first month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 2) {
                                            $responeText .= "Check Kedua \n";
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (20 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 2nd successful transaction in the first month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 5) {
                                            $responeText .= "Check Kelima \n";
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (30 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 5th successful transaction in the first month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 10) {
                                            $responeText .= "Check Kesepuluh \n";
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (40 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 10th successful transaction in the first month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        }
                                        break;
                                    case 1:
                                        $add1 = Carbon::parse($startdate)->addMonth(1)->toDateTimeString();
                                        $add2 = Carbon::parse($startdate)->addMonth(2)->toDateTimeString();
                                        $totalNominalJournal = $company->gpx_journals()->where('type',
                                            'in')->where('gxp_type', 'incentive')->where('created_at', '>',
                                            $add1)->where('created_at', '<=', $add2)->sum('nominal');
                                        $countOrder = $company->order()->wherehas('payment', function ($payment) {
                                            $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                        })->where('created_at', '>', $add1)->where('created_at', '<=',
                                            $add2)->whereIn('status', [1, 2, 3, 4])->count();
                                        if ($totalNominalJournal < $upTo) {
                                            $nominal = floor($procentase * (($order->total_amount - $order->fee_credit_card) - $order->fee));
                                            if ($totalNominalJournal + $nominal > $upTo) {
                                                $finalNominal = $upTo - $totalNominalJournal;

                                            } else {
                                                $finalNominal = $nominal;
                                            }
                                            $journalGxp = JournalGXP::create([
                                                'description' => 'Insentif '.$procentase.'% month'.($diff + 1),
                                                'nominal' => $finalNominal,
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'id_company' => $order->id_company,
                                                'gxp_type' => 'incentive',
                                                'type' => 'in'
                                            ]);
                                        }
                                        if ($countOrder === 1) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (10 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 1st successful transaction in the second month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 2) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (20 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 2nd successful transaction in the second month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 5) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (30 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 5th successful transaction in the second month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 10) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (40 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 10th successful transaction in the second month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        }
                                        break;
                                    case 2:
                                        $add1 = Carbon::parse($startdate)->addMonth(2)->toDateTimeString();
                                        $add2 = Carbon::parse($startdate)->addMonth(3)->toDateTimeString();
                                        $totalNominalJournal = $company->gpx_journals()->where('type',
                                            'in')->where('gxp_type', 'incentive')->where('created_at', '>',
                                            $add1)->where('created_at', '<=', $add2)->sum('nominal');
                                        $countOrder = $company->order()->wherehas('payment', function ($payment) {
                                            $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                        })->where('created_at', '>', $add1)->where('created_at', '<=',
                                            $add2)->whereIn('status', [1, 2, 3, 4])->count();
                                        if ($totalNominalJournal < $upTo) {
                                            $nominal = floor($procentase * (($order->total_amount - $order->fee_credit_card) - $order->fee));
                                            if ($totalNominalJournal + $nominal > $upTo) {
                                                $finalNominal = $upTo - $totalNominalJournal;

                                            } else {
                                                $finalNominal = $nominal;
                                            }
                                            $journalGxp = JournalGXP::create([
                                                'description' => 'Insentif '.$procentase.'% month'.($diff + 1),
                                                'nominal' => $finalNominal,
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'id_company' => $order->id_company,
                                                'gxp_type' => 'incentive',
                                                'type' => 'in'
                                            ]);
                                        }
                                        if ($countOrder === 1) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (10 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 1st successful transaction in the third month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 2) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (20 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 2nd successful transaction in the third month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 5) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (30 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 5th successful transaction in the third month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        } elseif ($countOrder === 10) {
                                            $cashback = CashBackVoucher::create([
                                                'id_company' => $order->id_company,
                                                'nominal' => (40 / 100) * $nominalCashbackVoucher,
                                                'description' => 'Cash Back 10th successful transaction in the third month',
                                                'currency' => $order->order_detail->currency,
                                                'rate' => $order->order_detail->rate,
                                                'expired_at' => Carbon::now()->addMonth(1)->toDateTimeString(),
                                                'status' => 'active'
                                            ]);
                                        }
                                        break;
                                }
                            }
                            $product_service = app('\App\Services\ProductService');
                            $product_service->allMailCustomer($order->id_company, $order->invoice_no);
                            $product_service->sendWACustomer($order->invoice_no);

                            //Send Notification Receive Payment
                            $company = Company::find($order->id_company);
                            \DB::commit();
                            if (!empty($company->email_company) && $order) {
                                $product_service->allMailProvider($order->id_company, $order->invoice_no);


                            }
                            $product_service->sendWAProvider( $order->invoice_no);
                            \Log::info('WAProvider from'.AutoOrderMidtrans::class.' line 328');
                            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : \request()->ip();
                            $loc = \Stevebauman\Location\Facades\Location::get($ip);
                            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                            if ($order->booking_type =='online'){
                                $content = '**New SETTLED Online Booking '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                            }else{
                                $content = '**New SETTLED E-Invoice '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                            }
                            $newCompany = $company;

                            $content .= '```';
                            $content .= "Company Name    : " . $newCompany->company_name . "\n";
                            $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
                            $content .= "Email Company   : " . $newCompany->email_company . "\n";
                            $content .= "Phone Number    : " . $newCompany->phone_company . "\n";
                            $content .= "Invoice Name    : " . $order->order_detail->product_name. "\n";
                            $content .= "Customer Name   : " . $order->customer_info->first_name. "\n";
                            $content .= "Customer Email  : " . $order->customer_info->email. "\n";
                            $content .= "Total Nominal   : " . format_priceID($order->total_amount). "\n";
                            $content .= "Payment Method  : " . $order->payment->payment_gateway. "\n";
                            if($order->voucher):
                                $content .= "Use Voucher     :  Yes\n";
                                $content .= "Voucher Code    : ".$order->voucher_code. "\n";
                                $content .= "Voucher Amount  : ".format_priceID($order->voucher_amount). "\n";
                                if($order->voucher->by_gomodo == '1'):
                                    $content .= "Voucher By      :  Gomodo\n";
                                else:
                                    $content .= "Voucher By      :  Provider\n";
                                endif;
                            endif;
                            $content .= "IP Address      : " . $ip . "\n";
                            $content .= "City name       : " . $loc->cityName . "\n";
                            $content .= "Region Name     : " . $loc->regionName . "\n";
                            $content .= "Country Code    : " . $loc->countryCode . "\n";
                            $content .= '```';
                            $this->sendDiscordNotification(sprintf('%s', $content),'transaction');
                            $responeText .= "OK Success";
                            echo $responeText;

                        } else {
                            \DB::rollBack();
                        }
                    } else {
                        \DB::rollBack();
                    }

                } else {
                    \DB::rollBack();
//                    return 'error';
                }

            } catch (\Exception $exception) {
                echo $exception->getMessage();
                \Log::error($exception);
                \DB::rollBack();
            }

        }
    }
}
