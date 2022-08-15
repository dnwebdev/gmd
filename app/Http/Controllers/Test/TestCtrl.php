<?php

namespace App\Http\Controllers\Test;

use App\Jobs\SendEmail;
use App\Models\Company;
use App\Models\ListPayment;
use App\Models\CompanyToken;
use App\Models\Order;
use App\Models\UserAgent;
use App\Notifications\Register\CompanyRegister;
use App\Traits\CurrencyTrait;
use App\Traits\DiscordTrait;
use App\Traits\XenditTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TestCtrl extends Controller
{
    use XenditTrait, CurrencyTrait, DiscordTrait;

    public function test(Request $request)
    {
        if ($order = Order::where('invoice_no', $request->input('external_id'))->whereStatus('0')->first()) {
            $dataXenditCreditCard = [
                "external_id" => 'invoice - ' . $order->payment->reference_number,
                "amount" => $order->total_amount,
                'token_id' => $request->input('id'),
                'authentication_id' => $request->input('authentication_id'),
                'card_cvn' => $request->input('card_cvn'),
                'capture' => true
            ];

//            return apiResponse(200,$dataXenditCreditCard);
            $responseXendit = json_decode($this->XenditApi('credit-card', $dataXenditCreditCard));
            if (isset($responseXendit->error_code)) {
                return apiResponse(500, 'Something Wrong', $responseXendit);
            }

            if ($responseXendit->status == 'FAILED') {
                return apiResponse(500, ucfirst(strtolower(str_replace('_', ' ', $responseXendit->failure_reason))),
                    $responseXendit);
            }
            if ($responseXendit->status == 'CAPTURED') {
                $order->update(['status' => 1]);
                $product_service = app('\App\Services\ProductService');
                $product_service->allMailCustomer($order->id_company, $order->invoice_no);
                $product_service->sendWACustomer($order->invoice_no);
                $product_service->allMailProvider($order->id_company, $order->invoice_no);
                $product_service->sendWAProvider($order->invoice_no);
                \Log::info('WAProvider from'.TestCtrl::class.' line 54');
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
//                $this->send_invoice($order->id_company, $order->invoice_no);
//                $this->send_email_notif($order->id_company, $order->invoice_no);
                $newCompany = Company::find($order->id_company);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                if ($order->booking_type == 'online') {
                    $content = '**New Paid & Waiting for Settlement Online Booking ' . $order->invoice_no . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                } else {
                    $content = '**New Paid & Waiting for Settlement E-Invoice ' . $order->invoice_no . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                }
                $content .= '```';
                $content .= "Company Name    : " . $newCompany->company_name . "\n";
                $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
                $content .= "Email Company   : " . $newCompany->email_company . "\n";
                $content .= "Phone Number    : " . $newCompany->phone_company . "\n";
                $content .= "Invoice Name    : " . $order->order_detail->product_name . "\n";
                $content .= "Customer Name   : " . $order->customer_info->first_name . "\n";
                $content .= "Customer Email  : " . $order->customer_info->email . "\n";
                $content .= "Total Nominal   : " . format_priceID($order->total_amount) . "\n";
                $content .= "Payment Method  : " . $order->payment->payment_gateway . "\n";
                if ($order->voucher):
                    $content .= "Use Voucher     :  Yes\n";
                    $content .= "Voucher Code    : " . $order->voucher_code . "\n";
                    $content .= "Voucher Amount  : " . format_priceID($order->voucher_amount) . "\n";
                    if ($order->voucher->by_gomodo == '1'):
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
                $this->sendDiscordNotification(sprintf('%s', $content), 'transaction');


//                $this->sendDiscordNotification($content, 'transaction');
                return apiResponse(200, 'OK', $responseXendit);
            }

            return apiResponse(200, 'OK Broo', $responseXendit);
//            return apiResponse(200, 'OK Broo', $responseXenditCheck);
        }

        return apiResponse(404, 'KO');

    }

//    public function test(Request $request)
//    {
////        if ($order = Order::where('invoice_no',$request->input('external_id'))->whereStatus('0')->first()){
//        $dataXenditCreditCard = [
//            "external_id" => "INV190531484784",
//            "amount" => "133883",
//            'token_id' =>"5cf0ca4739bbc21026b58c72",
//            'authentication_id' => '5cf0ca4739bbc21026b58c73',
//        ];
//
////            return apiResponse(200,$dataXenditCreditCard);
//        return $this->XenditApi('credit-card', $dataXenditCreditCard);
////        }
//
//        return apiResponse(404,'KO');
//
//    }

    public function testView()
    {
        return view('test');
    }

    public function testViewCC(Request $request)
    {
        $data['company'] = Company::find(1);
        $year = [];
        for ($i = 0; $i < 11; $i++) {
            $year = Arr::add($year, Carbon::now()->addYear($i)->format('Y'), Carbon::now()->addYear($i)->format('y'));
        }
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $months = array_add($months, '0' . $i, '0' . $i);
            } else {
                $months = array_add($months, strval($i), (string)$i);
            }

        }
        $data['years'] = $year;
        $data['months'] = $months;
        $data['orderAds'] = null;
//        dd($data);

        return view('payment.credit-card', $data);
    }

    public function checkCC(Request $request)
    {
        $dataXenditCreditCard = [

        ];
        $response = json_decode($this->XenditApi('get-invoice', $dataXenditCreditCard, '5cdc00177a391c7329a59ff9'));
        if (isset($response->status)) {
            return $response->status;
        }
        return 'error';
    }

    public function payOVO($invoice, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required'
        ]);
        if ($validator->fails()) {
            return apiResponse(422, 'Invalid Data', null, $validator->errors());
        }
        if ($order = Order::where('invoice_no', $invoice)->whereStatus('0')->first()) {
            $dataOVO = [
                "external_id" => $invoice,
//                "amount" => 4444,
                "amount" => $order->total_amount,
                "ewallet_type" => "OVO",
                "phone" => $request->input('phone')
            ];
            $responseXendit = json_decode($this->XenditApi('ovo', $dataOVO));
            if (isset($responseXendit->error_code)) {
                $error_code = [
                    'API_VALIDATION_ERROR' => \trans('payment.validation-ovo.api_validation_error'),
                    'USER_DID_NOT_AUTHORIZE_THE_PAYMENT' => \trans('payment.validation-ovo.user_did_not_authorize_the_payment'),
                    'USER_DECLINED_THE_TRANSACTION' => \trans('payment.validation-ovo.user_declined_the_transaction'),
                    'PHONE_NUMBER_NOT_REGISTERED' => \trans('payment.validation-ovo.phone_number_not_registered'),
                    'EXTERNAL_ERROR' => \trans('payment.validation-ovo.external_error'),
                    'SENDING_TRANSACTION_ERROR' => \trans('payment.validation-ovo.sending_transaction_error'),
                    'EWALLET_APP_UNREACHABLE' => \trans('payment.validation-ovo.ewallet_app_unreachable'),
                    'OVO_TIMEOUT_ERROR' => \trans('payment.validation-ovo.ovo_timeout_error'),
                    'CREDENTIALS_ERROR' => \trans('payment.validation-ovo.credentials_error'),
                    'ACCOUNT_AUTHENTICATION_ERROR' => \trans('payment.validation-ovo.account_authentication_error'),
                    'ACCOUNT_BLOCKED_ERROR' => \trans('payment.validation-ovo.account_blocked_error'),
                    'DUPLICATE_PAYMENT' => \trans('payment.validation-ovo.duplicate_payment'),
                    'EWALLET_TYPE_NOT_SUPPORTED' => \trans('payment.validation-ovo.ewallet_type_not_supported'),
                    'REQUEST_FORBIDDEN_ERROR' => \trans('payment.validation-ovo.request_forbidden_error'),
                    'DEVELOPMENT_MODE_PAYMENT_ACKNOWLEDGED' => \trans('payment.validation-ovo.development_mode_payment_acknowledged'),
                    'DEVELOPMENT_MODE_PAYMENT_SIMULATION_ACKNOWLEDGED' => \trans('payment.validation-ovo.development_mode_payment_simulation_acknowledged'),
                    'DUPLICATE_PAYMENT_REQUEST_ERROR' => \trans('payment.validation-ovo.duplicate_payment_request_error')
                ];
                if (empty($error_code[$responseXendit->error_code])){
                    return apiResponse(500, \trans('general.whoops'), $responseXendit);
                }
                return apiResponse(500, \trans('general.whoops') . ', ' . $error_code[$responseXendit->error_code] ?? '', $responseXendit);
            }

            switch ($order->payment->payment_gateway) {
                case 'Xendit OVO':
                    $code_payment = 'ovo_live';
                    break;
            }
            $list = ListPayment::where('code_payment', $code_payment)->first();
            $settlement = $list->settlement_duration;
            if (empty($list)) {
                $settlement = 2;
            }
            if (isset($responseXendit->status) && $responseXendit->status === 'PAID' || $responseXendit->status === 'COMPLETED') {
                $order->update(['status' => 1]);
                $order->payment->update([
                    'response' => $responseXendit,
                    'status' => $responseXendit->status,
                    'reference_number' => $responseXendit->business_id,
                    'settlement_on' => now()->addDays($settlement)->toDateTimeString(),
                    'received_amount' => $responseXendit->amount,
                    'amount' => $responseXendit->amount
                ]);

                $product_service = app('\App\Services\ProductService');
                $product_service->allMailCustomer($order->id_company, $order->invoice_no);
                $product_service->sendWACustomer($order->invoice_no);
                $product_service->allMailProvider($order->id_company, $order->invoice_no);
                $product_service->sendWAProvider($order->invoice_no);
                \Log::info('WAProvider from'.TestCtrl::class.' line 216');
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $order->company;
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                if ($order->booking_type =='online'){
                    $content = '**New PAID & Waiting for settlement Online Booking '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                }else{
                    $content = '**New PAID & Waiting for settlement E-Invoice '.$order->invoice_no.' ' . Carbon::now()->format('d M Y H:i:s') . '**';
                }
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
//                $content .= "Country Code    : ".$loc->countryCode."\n";
                $content .= '```';

                $this->sendDiscordNotification($content, 'transaction');

                return apiResponse(200, 'Success', $responseXendit);
            } elseif (isset($responseXendit->status) && $responseXendit->status === 'PENDING') {
                $order->payment->update([
                    'response' => $responseXendit,
                    'status' => $responseXendit->status,
                    'reference_number' => $responseXendit->business_id,
                    'settlement_on' => now()->addDays($settlement)->toDateTimeString(),
                    'received_amount' => $responseXendit->amount,
                    'amount' => $responseXendit->amount
                ]);

                return apiResponse(200, 'Success', $responseXendit);
            } elseif (isset($responseXendit->status) && $responseXendit->status === 'FAILED') {
                $order->update([
                    'status' => 7
                ]);
                $order->payment->update([
                    'response' => $responseXendit,
                    'status' => $responseXendit->status,
                    'reference_number' => $responseXendit->business_id,
                    'amount' => $responseXendit->amount
                ]);
                return apiResponse(500, \trans('general.whoops'), $responseXendit);
            }
            return apiResponse(500, \trans('general.whoops'), $responseXendit);
        }
        return apiResponse(404, 'KO');
    }

    /**
     * @param $company
     * @param $id
     * @throws \Throwable
     */
    public function send_invoice($company, $id)
    {
        //$this->company= $request->get('my_company') ? $request->get('my_company') : 0;

        $company = Company::find($company);
        $order = Order::find($id);


        $attached = [];
//        if ($order->status == 1) {
        //Make PDF INVOICE
        // $pdf_view = view($company->active_theme->theme->source.'.invoicepdf',['company'=>$company,'order'=>$order])->render();
        // //$pdf_view = $company->active_theme->theme->source;
        // $pdf = $utility->make_pdf($pdf_view);
        // $attached = ['data'=>$pdf,'name'=>$order->invoice_no.'-'.$order->customer_info->first_name.'.pdf'];

//        }

        $data = ['company' => $company, 'order' => $order];

        $mail_server = \App\Models\EmailServer::where(['id_company' => $order->id_company, 'status' => true])->first();

        $mail_conf = null;
        if ($mail_server) {
            $mail_conf = [
                'driver' => 'smtp',
                'host' => $mail_server->smtp_host,
                'port' => $mail_server->smtp_port,
                'username' => $mail_server->username,
                'password' => $mail_server->password,
            ];
        }

        $to = $order->customer_info->email;
        $template = null;
        $subject = null;
        $pdf = null;
        //Send Mail INVOICE
        if ($order->status == 0) {
            $subject = "Order Invoice & Itinerary for " . $company->company_name;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.unpaidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.unpaidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfUnpaidBookingOffline';
            }
        } elseif ($order->status == 1) {
            $subject = "Booking for " . $company->company_name . " #" . $id;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 2 || $order->status == 3) {
            $subject = $company->company_name . " Tour On Progress #" . $id;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 8) {
            $subject = $company->company_name . " New Booking Inquiry #" . $id;
            if ($order->booking_type == 'online') {
                $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();
                $pdf = $company->active_theme->theme->source . '.booking-email';
            } else {
                $template = view($company->active_theme->theme->source . '.booking-offline-email', $data)->render();
                $pdf = $company->active_theme->theme->source . '.booking-offline-email';
            }
        } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
            $subject = $company->company_name . " #" . $id . " Booking Canceled";
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.cancelbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfCancelBooking';
            } else {
                $template = view('dashboard.company.order.mail.cancelbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
            }
        } else {
            $subject = $company->company_name . " Booking Completed #" . $id;
            if ($order->booking_type == 'online') {
                $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();
                $pdf = $company->active_theme->theme->source . '.booking-email';
            } else {
                $template = view($company->active_theme->theme->source . '.booking-offline-email', $data)->render();
                $pdf = $company->active_theme->theme->source . '.booking-offline-email';
            }
        }
//        Log::info($company);

//        $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();

        dispatch(new SendEmail($subject, $to, $template, $pdf, $data));
    }

//    public function send_invoice($company, $id)
//    {
//        //$this->company= $request->get('my_company') ? $request->get('my_company') : 0;
//
//        $company = \App\Models\Company::find($company);
//        $order = \App\Models\Order::find($id);
//
//
//        $attached = [];
//        if ($order->status == 1) {
//            //Make PDF INVOICE
//            // $pdf_view = view($company->active_theme->theme->source.'.invoicepdf',['company'=>$company,'order'=>$order])->render();
//            // //$pdf_view = $company->active_theme->theme->source;
//            // $pdf = $utility->make_pdf($pdf_view);
//            // $attached = ['data'=>$pdf,'name'=>$order->invoice_no.'-'.$order->customer_info->first_name.'.pdf'];
//
//        }
//
//        //Send Mail INVOICE
//        if ($order->status == 0) {
//            $booking = "Order Invoice & Itinerary for " . $company->company_name;
//        } elseif ($order->status == 1) {
//            $booking = "Booking for " . $company->company_name . " #" . $id;
//        } elseif ($order->status == 2 || $order->status == 3) {
//            $booking = $company->company_name . " Tour On Progress #" . $id;
//        } elseif ($order->status == 8) {
//            $booking = $company->company_name . " New Booking Inquiry #" . $id;
//        } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
//            $booking = $company->company_name . " #" . $id . " Booking Canceled";
//        } else {
//            $booking = $company->company_name . " Booking Completed #" . $id;
//        }
////        Log::info($company);
//        $data = ['company' => $company, 'order' => $order];
//
//        $mail_server = \App\Models\EmailServer::where(['id_company' => $order->id_company, 'status' => true])->first();
//
//        $mail_conf = null;
//        if ($mail_server) {
//            $mail_conf = [
//                'driver' => 'smtp',
//                'host' => $mail_server->smtp_host,
//                'port' => $mail_server->smtp_port,
//                'username' => $mail_server->username,
//                'password' => $mail_server->password,
//            ];
//        }
//
//        $subject = $booking;
//        $to = $order->customer_info->email;
//        if ($order->booking_type == 'offline') {
//            $template = view($company->active_theme->theme->source . '.booking-offline-email', $data)->render();
//        } else {
//            $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();
//        }
//
//
//        dispatch(new SendEmail($subject, $to, $template));
//    }


    /**
     * send email notif to company
     * @param $company
     * @param $id
     * @throws \Throwable
     */
    public function send_email_notif($company, $id)
    {
        $company = Company::find($company);
        $order = Order::find($id);


        $email_view_data = ['company' => $company, 'order' => $order];

        $to = $order->company->email_company;
        $template = null;
        $subject = null;
        $pdf = null;
        if (!empty($company->email_company) && $order) {
            if ($order->status == 1) {
                $subject = "New Confirmed Booking #" . $id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.paidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 0) {
                $subject = "Unpaid Booking #" . $id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.unpaidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.unpaidprovider', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfUnpaidProvider';
                }
            } elseif ($order->status == 2 || $order->status == 3) {
                $subject = "Tour On Process #" . $id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.paidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 8) {
                $subject = "New Booking Inquiry " . $id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotif';
                } else {
                    $template = view('dashboard.company.order.emailnotiforderoffline', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotiforderoffline';
                }
            } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
                $subject = "#" . $id . " Booking Canceled";
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.cancelprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfCancelProvider';
                } else {
                    $template = view('dashboard.company.order.mail.cancelbookingoffline', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
                }
            } else {
                $subject = "#" . $id . " Booking Complete!";
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotif';
                } else {
                    $template = view('dashboard.company.order.emailnotiforderoffline', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotiforderoffline';
                }
            }

            dispatch(new SendEmail($subject, $to, $template, $pdf, $email_view_data));
        }

    }

//    public function send_email_notif($company, $id)
//    {
//        $company = \App\Models\Company::find($company);
//        $order = \App\Models\Order::find($id);
//
//
//        if (!empty($company->email_company) && $order) {
//            if ($order->status == 1) {
//                $booking = "New Confirmed Booking #" . $id;
//            } elseif ($order->status == 0) {
//                $booking = "Unpaid Booking #" . $id;
//            } elseif ($order->status == 2 || $order->status == 3) {
//                $booking = "Tour On Process #" . $id;
//            } elseif ($order->status == 8) {
//                $booking = "New Booking Inquiry " . $id;
//            } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
//                $booking = "#" . $id . " Booking Canceled";
//            } else {
//                $booking = "#" . $id . " Booking Complete!";
//            }
//            //Send Mail INVOICE
//            // $email_view = $company->active_theme->theme->source.'.booking-email';
//            $email_view_data = ['company' => $company, 'order' => $order];
//
//            $subject = $booking;
//            $to = $order->company->email_company;
//            if ($order->booking_type == 'offline') {
//                $template = view('dashboard.company.order.emailnotiforderoffline', $email_view_data)->render();
//            } else {
//                $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
//            }
//
//            dispatch(new SendEmail($subject, $to, $template));
//        }
//
//    }

    public function getExchange(Request $request)
    {
//        dd($this->getExchangeRate(1,'IDR','USD'));

        $order = Order::latest()->first();
        $newCompany = \App\Models\Company::find($order->id_company);
        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
        if ($order->booking_type == 'online') {
            $content = '**New PAID Online Booking ' . $order->invoice_no . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
        } else {
            $content = '**New PAID E-Invoice ' . $order->invoice_no . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
        }

        $content .= '```';
        $content .= 'Company Name : ' . $newCompany->company_name . '
Domain Gomodo : ' . $http . $newCompany->domain_memoria . '
Email Company : ' . $newCompany->email_company . '
Invoice Name : ' . $order->order_detail->product_name . ' 
Total Nominal : ' . format_priceID($order->total_amount) . ' 
Payment method : ' . $order->payment->payment_gateway;
        if ($order->voucher) {
            $content .= ' 
Voucher : Yes ';
            $content .= '
Voucher Code : ' . $order->voucher_code;
            $content .= '
Voucher Amount : ' . format_priceID($order->voucher_amount);
            if ($order->voucher->by_gomodo == '1') {
                $content .= '
Voucher By : Gomodo';
            } else {
                $content .= '
Voucher By : Provider';
            }

        } else {
            $content .= '
Voucher: No';
        }

        $content .= '```';


        $this->sendDiscordNotification($content, 'transaction');
    }
}
