<?php

namespace App\Http\Controllers\Customer;

use App\Models\CashBackVoucher;
use App\Models\Company;
use App\Models\JournalGXP;
use App\Models\ListPayment;
use App\Models\Order;
use App\Traits\CustomInfoRulesTrait;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use Gomodo\Midtrans\MidTrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MidtransCtrl extends Controller
{
    use DiscordTrait, CustomInfoRulesTrait;

    public function __construct()
    {
        $this->middleware('company');
    }

    public function discordNotif($order, $company, $request)
    {
        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
        $loc = \Stevebauman\Location\Facades\Location::get($ip);
        if ($order->booking_type == 'online') {
            $content = '**New PAID Online Booking '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
            if ($order->payment->status == 'PENDING') {
                $content = '**New PAID & Waiting for Settlement Online Booking '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
            }
        } else {
            $content = '**New PAID E-Invoice '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
            if ($order->payment->status == 'PENDING') {
                $content = '**New PAID E-Invoice & Waiting for Settlement Online Booking '.$order->invoice_no.' '.Carbon::now()->format('d M Y H:i:s').'**';
            }
        }

        $content .= '```';
        $content .= "Company Name    : ".$company->company_name."\n";
        $content .= "Domain Gomodo   : ".$http.$company->domain_memoria."\n";
        $content .= "Email Company   : ".$company->email_company."\n";
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
        $product_service = app('\App\Services\ProductService');
        $product_service->allMailCustomer($order->id_company, $order->invoice_no);
        $product_service->sendWACustomer( $order->invoice_no);
        if (!empty($company->email_company) && $order) {
            $product_service->allMailProvider($order->id_company, $order->invoice_no);
        }
        $product_service->sendWAProvider( $order->invoice_no);
        \Log::info('WAProvider from'.MidtransCtrl::class.' line 71');
    }

    public function notificationMidtrans(Request $request)
    {
        $res = json_decode($request->response);
        if (!empty($res)) {
            $order = Order::where('invoice_no', $res->order_id)->first();
            // $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $http = env('APP_ENV') == 'production' ? 'https://' : 'http://';
            $company = $order->company;
            return redirect()->to($http.$company->domain_memoria.'/invoice/akulaku/'.$order->invoice_no)->send();
            // return redirect()->route('invoice.akulaku', ['no_invoice' => $res->order_id]);
        } else{
            $data = MidTrans::notification($request->all())->getNotification();
        }
        if ($data->error) {
            return apiResponse('400', $data->message);
        }

        try {
            $order = Order::whereHas('payment', function ($q) use ($data) {
                $q->where([
                    'invoice_no' => $data->data->order_id,
                    'amount' => $data->data->gross_amount
                ]);
            })->first();
            
            if ($order->status == 1) {
                $redirect = route('invoice.success', ['no_invoice' => $order->invoice_no]);
                return apiResponse($data->data->status_code, $data->message, ['redirect' => $redirect]);
            }

            if (!empty($order)) {
                $company = Company::find($order->id_company);
                $code_payment = '';
    
                switch ($order->payment->payment_gateway){
                    case 'Midtrans Indomaret':
                        $code_payment = 'indomaret';
                        break;
                    case 'Midtrans Alfamart':
                        $code_payment = 'alfamart_midtrans';
                        break;
                    case 'Midtrans Gopay':
                        $code_payment = 'gopay';
                        break;
                    case 'Midtrans Virtual Account BCA':
                        $code_payment = 'bca_va';
                        break;
                    case 'Midtrans AkuLaku':
                        $code_payment = 'akulaku';
                        break;
                }
                $list = ListPayment::where('code_payment', $code_payment)->first();
                $settlement_duration = $list->settlement_duration;
                $pay = Carbon::now()->addDays($settlement_duration);
                $day = $pay->format('N');
                
                if (empty($list)){
                    return [
                        'oke' => true,
                        'message' => 'No List Payment'
                    ];
                }

                if ($data->data->status_code === '200') {
                    $order->update([
                        'status' => 1
                    ]);
        
                    switch ($day) {
                        case 6 :
                            $pay->addDays(2);
                            $order->payment->update([
                                'status' => 'PENDING',
                                'settlement_on' => $pay->toDateTimeString(),
                                'received_amount' => ceil($data->data->gross_amount),
                                'response_midtrans' => $data
                            ]);
        
                            $this->discordNotif($order, $company, $request);
                            break;
                        case 7 :
                            $pay->addDays(1);
                            $order->payment->update([
                                'status' => 'PENDING',
                                'settlement_on' => $pay->toDateTimeString(),
                                'received_amount' => ceil($data->data->gross_amount),
                                'response_midtrans' => $data
                            ]);
        
                            $this->discordNotif($order, $company, $request);
                            break;
                        default:
                            if ($data->data->transaction_status == 'settlement') {
                                $order->payment->update([
                                    'status' => 'PAID',
                                    'pay_at' => Carbon::parse($data->data->transaction_time)->toDateTimeString(),
                                    'settlement_on' => Carbon::parse($data->data->transaction_time)->toDateTimeString(),
                                    'received_amount' => ceil($data->data->gross_amount),
                                    'response_midtrans' => $data
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
                                                'in')->where('gxp_type',
                                                'incentive')->where('created_at', '>',
                                                $startdate->toDateTimeString())->where('created_at', '<=',
                                                $add1)->sum('nominal');
        
                                            $countOrder = $company->order()
                                                ->wherehas('payment', function ($payment) {
                                                    $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                                })
                                                ->where('created_at', '>', $startdate->toDateTimeString())->where('created_at',
                                                    '<=',
                                                    $add1)
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
                                                'in')->where('gxp_type',
                                                'incentive')->where('created_at', '>', $add1)->where('created_at', '<=',
                                                $add2)->sum('nominal');
                                            $countOrder = $company->order()->wherehas('payment', function ($payment) {
                                                $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                            })->where('created_at', '>', $add1)->where('created_at', '<=',
                                                $add2)->whereIn('status',
                                                [1, 2, 3, 4])->count();
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
                                                'in')->where('gxp_type',
                                                'incentive')->where('created_at', '>', $add1)->where('created_at', '<=',
                                                $add2)->sum('nominal');
                                            $countOrder = $company->order()->wherehas('payment', function ($payment) {
                                                $payment->where('payment_gateway', '!=', 'Redeem Voucher');
                                            })->where('created_at', '>', $add1)->where('created_at', '<=',
                                                $add2)->whereIn('status',
                                                [1, 2, 3, 4])->count();
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
                                $this->discordNotif($order, $company, $request);
                                // if ($order->payment->payment_gateway == 'Midtrans Gopay'){
                                // }
                                $redirect = route('invoice.success', ['no_invoice' => $order->invoice_no]);
                                return apiResponse($data->data->status_code, $data->message, ['redirect' => $redirect]);
                                // return apiResponse($data->data->status_code, $data->message);
                            } else{
                                $order->payment->update([
                                    'status' => 'PENDING',
                                    'settlement_on' => $pay->toDateTimeString(),
                                    'received_amount' => ceil($data->data->gross_amount),
                                    'response_midtrans' => $data
                                ]);
        
                                $this->discordNotif($order, $company, $request);
                                return apiResponse($data->data->status_code, $data->message);
                            }
                    }
                } elseif ($data->data->status_code === '201') {
                    if ($code_payment == 'akulaku') {
                        $order->payment->update([
                            'status' => 'PENDING',
                            'settlement_on' => $pay->toDateTimeString(),
                            'received_amount' => ceil($data->data->gross_amount),
                            'reference_number' => $data->data->transaction_id,
                            'response_midtrans' => $data,
                            'response' => $data,
                        ]);
                        
                        // return redirect()->route('invoice.akulaku', ['no_invoice' => $order->invoice_no]);
                        $redirect = route('invoice.akulaku', ['no_invoice' => $order->invoice_no]);
                        return apiResponse($data->data->status_code, $data->message, ['redirect' => $redirect]);
                    } else{
                        $order->payment->update([
                            'settlement_on' => $pay->toDateTimeString(),
                            'received_amount' => ceil($data->data->gross_amount),
                            'reference_number' => $data->data->transaction_id,
                            'response_midtrans' => $data,
                        ]);
                    }
                    return apiResponse($data->data->status_code, $data->message);
                } elseif ($data->data->status_code === '202' || in_array(substr($data->data->status_code,0,1), ['3','4','5'])) {
                    $order->payment->update([
                        'status' => 'CANCEL BY SYSTEM',
                        'response_midtrans' => $data,
                    ]);
        
                    $order->update([
                        'status' => 7
                    ]);
                    return apiResponse($data->data->status_code, $data->message);
                } else {
                    return apiResponse($data->data->status_code, $data->message);
                }
            } else {
                return apiResponse(404, 'Order is invalid');
            }
        } catch (\Exception $e) {
            return apiResponse(500, $e->getMessage());
        }
        
    }
}
