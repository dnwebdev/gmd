<?php

namespace App\Http\Controllers;

use App\Traits\DiscordTrait;
use Mail;
use PDF;
use App\Models\CashBackVoucher;
use App\Models\Company;
use App\Models\JournalGXP;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use DiscordTrait;
    /**
     * function process xendit Premium Store
     *
     * @param  mixed $data
     * @param  mixed $json_data
     *
     * @return void
     */
    public function xenditPremiumStore($data, $json_data){

        $orderAds = \App\Models\OrderAds::where(['reference_number'=> $data->id,
            'no_invoice' => $data->external_id,
            'amount_payment' => $data->amount,
        ])->whereNotIn('status', [1,2,3])->first();

        if($orderAds){

            $orderAds->payment_method = $data->payment_method;
            if(isset($data->bank_code)){
                $orderAds->bank_code = $data->bank_code;
            }

            $orderAds->response = $json_data;
            $orderAds->received_amount = $data->adjusted_received_amount;
            $orderAds->save();

            if($data->status == 'PAID'){
                $orderAds->status = 1;
                $orderAds->save();

                if ($orderAds->payment_gateway == 'Xendit Credit Card' || $orderAds->payment_gateway == 'Xendit Alfamart') {
                    $orderAds->update(['status_payment' => 'PENDING']);
                    $response = ['status' => 200, 'message' => 'ok', 'data' => []];
                } else {
                    $orderAds->update(['status_payment' => 'PAID']);
                    $response = ['status' => 200, 'message' => 'completed', 'data' => ['invoice' => $orderAds->no_invoice]];
                }

                // Sendmail to provider
                $user_agent = \App\Models\UserAgent::find($orderAds->adsOrder->company_id);
                $company = Company::find($orderAds->adsOrder->company_id);
                $to = "store@mygomodo.com";
                $dataEmail = [
                    'category_ads' => $orderAds->category_ads,
                    'email_company' => $user_agent->email,
                    'first_name' => $user_agent->first_name,
                    'company_name' => $company->company_name,
                    'no_invoice' => $orderAds->no_invoice,
                    'date_active' => date('d M Y', strtotime($orderAds->adsOrder->start_date)).' - '.date('d M Y', strtotime($orderAds->adsOrder->end_date)). ' Hari',
                    'total_price' => $orderAds->total_price,
                    'time_updated_at' => $orderAds->updated_at->format('h:i a'),
                    'updated_at' => $orderAds->updated_at->format('d-m-Y'),
                    'orderAds' => $orderAds,
                ];

                $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mail.mail_paid', $dataEmail);
                Mail::send('dashboard.company.ads.mail.mail_paid', $dataEmail, function($message) use ($dataEmail, $to, $pdf){
                    $message->to($dataEmail['email_company'])->subject('Order marketing solutions :'. $dataEmail['category_ads'].' (Status PAID)');
                    $message->attachData($pdf->output(), 'Order ' . $dataEmail['category_ads'] . ' Invoice - #' . $dataEmail['no_invoice'] . '.pdf');
                    $message->from($to, 'Admin Gomodo');
                });

                $dataPdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mail.mailPaidAdmin', $dataEmail);
                Mail::send('dashboard.company.ads.mail.mailPaidAdmin', $dataEmail, function ($message) use ($dataPdf, $dataEmail, $to) {
                    $message->to($to)->subject('Order ' . $dataEmail['category_ads'] . 'from :' . $dataEmail['company_name'].' (Status PAID)');
                    $message->attachData($dataPdf->output(), "Data Order Provider -" . $dataEmail['company_name'] . ".pdf");
                    $message->from($dataEmail['email_company'], 'Order Premium');
                });

                $content = '** New PAID Order Layanan Promosi **';
                $content .= '```';
                $content .= 'Detail Order Created : ' . Carbon::now()->format('d M Y H:i:s'). '
Company Name : '. $company->company_name.'
Name : '. $user_agent->first_name.'
Url : '. $orderAds->adsOrder->url.'
Product Name : ' . $orderAds->category_ads.'
Status : '. $orderAds->status_payment.'
Payment Method : '. $orderAds->payment_gateway.'
Sub Total : '. format_priceID($orderAds->amount).'
Service Fee : '. format_priceID($orderAds->adsOrder->service_fee).'
Total : '.format_priceID($orderAds->total_price);
                if ($orderAds->promoAds){
                    $content.='
Voucher Promo Code : '.$orderAds->promoAds->code;
                    $content.='
Voucher Promo Amount : '.format_priceID($orderAds->voucher);
                    $content.='
Voucher Promo By : Gomodo';

                }else{
                    $content.='
Voucher Promo: No';
                }
                if ($orderAds->voucherAds){
                    $content.='
Voucher Cashback Amount : '.format_priceID($orderAds->voucherAds->nominal);
                }else{
                    $content.='
Voucher Cashback: No';
                }
                if ($orderAds->gxp_amount){
                    $content.='
Gxp Amount : '.format_priceID($orderAds->gxp_amount);
                }else{
                    $content.='
Gxp : No';
                }
                $content .= '```';

            $this->sendDiscordNotification($content, 'store');

                $response = [
                    'status'=>200,
                    'message'=>'completed',
                    'data'=>['invoice'=>$orderAds->no_invoice]
                ];
            }else if($data->status == 'EXPIRED'){

                $orderAds->status = 4;
                $orderAds->save();

                if($orderAds->gxp_amount){
                    $journalGxp = JournalGXP::create([
                        'description' => 'Refund GXP ' . $orderAds->gxp_amount,
                        'nominal' => $orderAds->gxp_amount,
                        'currency' => 'IDR',
                        'rate' => 1,
                        'id_company' => $orderAds->adsOrder->company_id,
                        'gxp_type' => 'incentive',
                        'type' => 'in'
                    ]);
                }

                $response = [
                    'status' => 200,
                    'message' => 'ok',
                    'data' => []
                ];
            }else if($data->status == 'SETTLED'){

                if ($orderAds->payment_gateway == 'Xendit Credit Card') {
                    $orderAds->status = 1;
                    $orderAds->save();
                    $orderAds->update(['status_payment' => 'SETTLED']);
                    $response = ['status' => 200, 'message' => 'completed', 'data' => ['invoice' => $orderAds->no_invoice]];

                } else {
                    $response = ['status' => 200, 'message' => 'ok', 'data' => []];
                }

            }else{
                $response = [
                    'status' => 200,
                    'message' => 'ok',
                    'data' => []
                ];
            }

        } else{
            $response = [
                'status'=>404,
                'message'=>'Order premium is invalid',
                'data'=>[]
            ];
        }
        return json_encode($response);
        // return response()->json($data);
    }


    /**
     * function process xendit accept payment
     *
     * @return void
     */
    public function xendit_accept_payment()
    {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        if(substr($data->external_id,0,4) =='PREM'){
            return $this->xenditPremiumStore($data, $json_data);
        }
        
        $xendit = app('App\Services\XenditService');
        $res = json_decode($xendit->accept_payment());
        //Add Journal
        if ($res->status == 200 || $res->status == 200 && $res->message == 'completed') {
            $journal = app('App\Services\JournalService');
            $order = \App\Models\Order::find($res->data->invoice);
            $journal->add(['id_company' => $order->id_company,
                'journal_code' => $order->invoice_no,
                'journal_type' => 100,
                'description' => 'Order Product : ' . $order->order_detail->product_name,
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
                        $totalNominalJournal = $company->gpx_journals()->where('type', 'in')->where('gxp_type', 'incentive')->where('created_at', '>', $startdate->toDateTimeString())->where('created_at', '<=', $add1)->sum('nominal');

                        $countOrder = $company->order()
                            ->wherehas('payment', function ($payment) {
                                $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                            })
                            ->where('created_at', '>', $startdate->toDateTimeString())->where('created_at', '<=', $add1)
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
                                'description' => 'Insentif ' . $procentase . '% month' . ($diff + 1),
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
                        $totalNominalJournal = $company->gpx_journals()->where('type', 'in')->where('gxp_type', 'incentive')->where('created_at', '>', $add1)->where('created_at', '<=', $add2)->sum('nominal');
                        $countOrder = $company->order()->wherehas('payment', function ($payment) {
                            $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                        })->where('created_at', '>', $add1)->where('created_at', '<=', $add2)->whereIn('status', [1, 2, 3, 4])->count();
                        if ($totalNominalJournal < $upTo) {
                            $nominal = floor($procentase * (($order->total_amount - $order->fee_credit_card) - $order->fee));
                            if ($totalNominalJournal + $nominal > $upTo) {
                                $finalNominal = $upTo - $totalNominalJournal;

                            } else {
                                $finalNominal = $nominal;
                            }
                            $journalGxp = JournalGXP::create([
                                'description' => 'Insentif ' . $procentase . '% month' . ($diff + 1),
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
                        $totalNominalJournal = $company->gpx_journals()->where('type', 'in')->where('gxp_type', 'incentive')->where('created_at', '>', $add1)->where('created_at', '<=', $add2)->sum('nominal');
                        $countOrder = $company->order()->wherehas('payment', function ($payment) {
                            $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                        })->where('created_at', '>', $add1)->where('created_at', '<=', $add2)->whereIn('status', [1, 2, 3, 4])->count();
                        if ($totalNominalJournal < $upTo) {
                            $nominal = floor($procentase * (($order->total_amount - $order->fee_credit_card) - $order->fee));
                            if ($totalNominalJournal + $nominal > $upTo) {
                                $finalNominal = $upTo - $totalNominalJournal;

                            } else {
                                $finalNominal = $nominal;
                            }
                            $journalGxp = JournalGXP::create([
                                'description' => 'Insentif ' . $procentase . '% month' . ($diff + 1),
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
            if ($res->status ==200){
                $order = \App\Models\Order::find($res->data->invoice);
                $newCompany =  \App\Models\Company::find($order->id_company);
//                $http = env('HTTPS',false)==true?'https://':'http://';
//                if ($order->booking_type =='online'){
//                    $content = '**New PAID Online Booking '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
//                }else{
//                    $content = '**New PAID E-Invoice '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
//                }
//
//                $content .= '```';
//                $content .= 'Company Name : ' . $newCompany->company_name . '
//Domain Gomodo : '.$http . $newCompany->domain_memoria . '
//Email Company : ' . $newCompany->email_company . '
//Invoice Name : '.$order->order_detail->product_name.'
//Total Nominal : '.format_priceID($order->total_amount).'
//Payment method : '.$order->payment->payment_gateway;
//                if ($order->voucher){
//                    $content.='
//Voucher : Yes ';
//                    $content.='
//Voucher Code : '.$order->voucher_code;
//                    $content.='
//Voucher Amount : '.format_priceID($order->voucher_amount);
//                    if ($order->voucher->by_gomodo=='1'){
//                        $content.='
//Voucher By : Gomodo';
//                    }else{
//                        $content.='
//Voucher By : Provider';
//                    }
//
//                }else{
//                    $content.='
//Voucher: No';
//                }
//
//                $content .= '```';

                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : \request()->ip();
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                if ($order->booking_type =='online'){
                    $content = '**New PAID & Waiting for Settlement Online Booking '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                }else{
                    $content = '**New Paid & Waiting for Settlement E-Invoice '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                }

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
            }
            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer($order->id_company, $order->invoice_no);
            $product_service->sendWACustomer( $order->invoice_no);
//            $product_service->send_invoice($order->id_company, $order->invoice_no);


            //Send Notification Receive Payment
            $company = \App\Models\Company::find($order->id_company);

            if (!empty($company->email_company) && $order) {

                $product_service = app('\App\Services\ProductService');
                $product_service->allMailProvider($order->id_company, $order->invoice_no);

//                $product_service->send_email_notif($order->id_company, $order->invoice_no);

//                $utility = app('\App\Services\UtilityService');
//                $data = ['company' => $company, 'order' => $order];
//                $utility->send_mail($company->email_company, 'New Payment Received No #' . $order->invoice_no, 'dashboard.company.order.mail.paymentsuccessnotif', $data);
            }
            $product_service->sendWAProvider( $order->invoice_no);
            \Log::info('WAProvider from'.PaymentController::class.' line 541');
            $responeText.="OK Success";
            return response($responeText);

        }

        return response()->json($res);
    }


    /**
     * function process make invoice
     *
     * @param  mixed $invoice
     *
     * @return void
     */
    public function make_invoice($invoice)
    {

        $xendit = app('\App\Services\XenditService');
        $service = json_decode($xendit->make_invoice($invoice));
        return response()->json($service);
    }
}
