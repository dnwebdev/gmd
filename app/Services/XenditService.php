<?php

namespace App\Services;

use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditService
{
    /**
     * Declaration api xendit
     *
     */
    private $base_url = "https://api.xendit.co/v2/";
    private $xendit_key;


    public function __construct()
    {
        $this->xendit_key = env('XENDIT_KEY');
    }

    /**
     * make_invoice
     *
     * @param  mixed  $invoice
     *
     * @return void
     */
    public function make_invoice($invoice)
    {
        $order = \App\Models\Order::find($invoice);
        $data = [
            "external_id" => $order->invoice_no,
            "amount" => $order->total_amount,
            "payer_email" => $order->customer_info->email,
            "description" => "INVOICE #".$order->invoice_no.' - '.$order->order_detail->product_name
        ];
        $data_string = json_encode($data);
        $url = $this->base_url.'invoices';
        $res = json_decode($this->post_curl($url, $data_string));
        if (isset($res->error_code)) {
            return json_encode(['status' => 403, 'message' => $res->message]);
        }

        \App\Models\Payment::create([
            'invoice_no' => $invoice,
            'payment_gateway' => 'Xendit',
            'reference_number' => $res->id,
            'status' => $res->status,
            'amount' => $res->amount,
            'invoice_url' => $res->invoice_url,
            'expiry_date' => date('Y-m-d H:i:s', strtotime($res->expiry_date)),
            'created_at' => date('Y-m-d H:i:s', strtotime($res->created)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($res->updated)),
        ]);

        return json_encode(['status' => 200, 'message' => 'OK', 'data' => ['invoice_url' => $res->invoice_url]]);
    }

    /**
     * process function make disbursement
     *
     * @param  mixed  $data
     *
     * @return void
     */
    public function make_disbursement($data)
    {
        $data_string = json_encode($data);
        $res = json_decode($this->post_curl('https://api.xendit.co/disbursements', $data_string));
        if (isset($res->error_code)) {
            return json_encode(['status' => 520, 'message' => $res->message]);
        } else {
            return json_encode(['status' => 200, 'message' => 'OK', 'data' => $res]);
        }
    }

    /**
     * process function post curl
     *
     * @param  mixed  $url
     * @param  mixed  $data_string
     *
     * @return void
     */
    private function post_curl($url, $data_string)
    {

        $headers = array(
            'Content-Type:application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->xendit_key.":");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: '.strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        return $result;
    }

    /**
     * process function accept payment
     *
     * @return void
     */
    public function accept_payment()
    {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        
        $order = \App\Models\Order::whereHas('payment', function ($q) use ($data) {
            $q->where([
                'reference_number' => !empty($data->ewallet_type) ? optional($data)->business_id : $data->id,
                'invoice_no' => $data->external_id,
                'amount' => $data->amount
            ]);
        })
            ->whereDoesntHave('payment', function ($p) {
                $p->where('status', 'PAID');
            })
//            ->whereNotIn('status', [2, 3, 4])
            ->first();
        
        if ($order) {
            // $id = $data->id;
            $p = \App\Models\Payment::where([
                'reference_number' => $order->payment->payment_gateway == 'Xendit OVO' ? $data->business_id : $data->id,
                'invoice_no' => $data->external_id,
                'amount' => $data->amount
            ])->first();

            $p->payment_method = $order->payment->payment_gateway == 'Xendit OVO' ? 'OVO' : $data->payment_method;
            if (isset($data->bank_code)) {
                $p->bank_code = $data->bank_code;
            }
            $p->response = $json_data;
            if (isset($data->adjusted_received_amount)) {
                $p->received_amount = $data->adjusted_received_amount;
            }
            $p->status = $order->payment->payment_gateway == 'Xendit OVO' ? 'PENDING' : $data->status;
            $p->save();

            if (in_array($data->status, ['PAID', 'COMPLETED'])) {
                $order->status = 1;
                $order->save();
                if (in_array($order->payment->payment_gateway, ['Xendit Credit Card', 'Xendit Alfamart', 'Xendit OVO'])) {
                    $order->payment->update(['status' => 'PENDING']);
                    $response = ['status' => 200, 'message' => 'ok', 'data' => ['invoice' => $order->invoice_no]];
                } else {
                    $order->payment->update(['status' => 'PENDING']);
                    $response = [
                        'status' => 200,
                        'message' => 'completed',
                        'data' => ['invoice' => $order->invoice_no]
                    ];
                }
//                $order->payment->update(['status'=>'SETTLED']);
//                $response = ['status' => 200, 'message' => 'completed', 'data' => ['invoice' => $order->invoice_no]];

            } elseif ($data->status == 'SETTLED') {
//                if ($order->payment->payment_gateway == 'Xendit Credit Card') {
//                    $order->status = 1;
//                    $order->save();
//                    $order->payment->update(['status' => 'PAID']);
//                    $response = ['status' => 200, 'message' => 'completed', 'data' => ['invoice' => $order->invoice_no]];
//
//                } else {
                $response = ['status' => 200, 'message' => 'ok', 'data' => ['invoice' => $order->invoice_no]];
//                }
            } else {
                $response = ['status' => 200, 'message' => 'ok', 'data' => ['invoice' => $order->invoice_no]];
            }
        } else {
            $response = ['status' => 404, 'message' => 'Order is invalid', 'data' => []];
        }

        return json_encode($response);
    }

    /**
     * process function accept disbursement
     *
     * @return void
     */
    public function accept_disbursement()
    {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);

        $withdrawal = \App\Models\WithdrawRequest::where([
            'document_no' => $data->external_id,
            'amount' => $data->amount
        ])->orderBy('created_at', 'desc')->first();

        if ($withdrawal) {
            if ($data->status == 'COMPLETED') {
                $withdrawal->status = 1;
                $withdrawal->save();
            }

            if ($data->status == 'FAILED') {
                $withdrawal->status = 2;
                $withdrawal->save();
            }

            if ($data->status == 'COMPLETED') {
                $response = [
                    'status' => 200,
                    'message' => 'completed',
                    'data' => [
                        'document_no' => $data->external_id,
                        'id_company' => $withdrawal->id_company,
                        'amount' => $withdrawal->amount
                    ]
                ];
            } elseif ($data->status == 'FAILED') {
                switch ($data->failure_code) {
                    case 'INSUFFICIENT_BALANCE':
                        $reason = 'The balance in your account is insufficient to make the disbursement in the desired amount';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'UNKNOWN_BANK_NETWORK_ERROR':
                        $reason = 'The bank networks have returned an unknown error to us. We are unable to predict whether the disbursement will succeed should you retry the same disbursement request';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'TEMPORARY_BANK_NETWORK_ERROR':
                        $reason = 'The bank networks are experiencing a temporary error. Please retry the disbursement in 1-3 hours';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'INVALID_DESTINATION':
                        $reason = 'The banks have reported that the destination account is unregistered or blocked. If unsure about this, please retry again or contact the destination bank directly regarding the status of the destination account';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'SWITCHING_NETWORK_ERROR':
                        $reason = 'At least one of the switching networks is encountering an issue. Please retry the disbursement in 1-3 hours';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'REJECTED_BY_BANK':
                        $reason = 'The bank has rejected this transaction for unclear reasons. We are unable to predict whether the disbursement will succeed should you retry the same disbursement request';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'TRANSFER_ERROR':
                        $reason = 'We’ve encountered a fatal error while processing this disbursement. Certain API fields in your request may be invalid. Please contact our customer support team for more information';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    case 'TEMPORARY_TRANSFER_ERROR':
                        $reason = 'We’ve encountered a temporary issue while processing this disbursement. Please retry the disbursement in 1-2 hours';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                        break;
                    default:
                        $reason = 'The bank networks are experiencing a temporary error. Please retry the disbursement in 1-3 hours';
                        $response = [
                            'status' => 200,
                            'message' => 'failed',
                            'data' => [
                                'document_no' => $data->external_id,
                                'id_company' => $withdrawal->id_company,
                                'amount' => $withdrawal->amount,
                                'reason' => $reason
                            ]
                        ];
                }
            } else {
                $response = [
                    'status' => 200,
                    'message' => 'processed',
                    'data' => [
                        'document_no' => $data->external_id,
                        'id_company' => $withdrawal->id_company,
                        'amount' => $withdrawal->amount
                    ]
                ];
            }
        } else {
            $response = ['status' => 404, 'message' => 'Withdrawal is invalid', 'data' => []];
        }
        return json_encode($response);
    }

    /**
     * process function send Email
     *
     * @param  mixed  $noInvoice
     * @param  mixed  $forCompany
     *
     * @return void
     */
//    public function sendEmail($noInvoice, $forCompany = false)
//    {
//
//        $order = \App\Models\Order::where(['invoice_no', $noInvoice]);
//        if ($order) {
//            $company = $order->company;
//            if ($forCompany) {
//                $subject = 'New Paid Booking';
//                $to = $company->email_company;
//                $email_view_data = ['company' => $company, 'order' => $order];
//                $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
//                dispatch(new SendEmail($subject, $to, $template));
//
//            } else {
//                $booking = "Booking for " . $company->company_name . " #" . $noInvoice;
//                $data = ['company' => $company, 'order' => $order];
//                $mail_server = \App\Models\EmailServer::where(['id_company' => $order->id_company, 'status' => true])->first();
//                $mail_conf = null;
//                if ($mail_server) {
//                    $mail_conf = [
//                        'driver' => 'smtp',
//                        'host' => $mail_server->smtp_host,
//                        'port' => $mail_server->smtp_port,
//                        'username' => $mail_server->username,
//                        'password' => $mail_server->password,
//                    ];
//                }
//
//                $subject = $booking;
//                $to = $order->customer_info->email;
//                $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();
//
//                dispatch(new SendEmail($subject, $to, $template));
//            }
//        }
//
//    }

    public function sendEmail($noInvoice, $forCompany = false)
    {

        $order = \App\Models\Order::where(['invoice_no', $noInvoice]);
        if ($order) {
            $company = $order->company;
            if ($forCompany) {
                $subject = 'New Paid Booking';
                $to = $company->email_company;
                $email_view_data = ['company' => $company, 'order' => $order];
                $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
                dispatch(new SendEmail($subject, $to, $template));

            } else {
                $booking = "Booking for ".$company->company_name." #".$noInvoice;
                $data = ['company' => $company, 'order' => $order];
                $mail_server = \App\Models\EmailServer::where([
                    'id_company' => $order->id_company,
                    'status' => true
                ])->first();
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

                if ($order->status == 0) {
                    $subject = "Order Invoice & Itinerary for ".$company->company_name;
                    if ($order->booking_type == 'online') {
                        $template = view('dashboard.company.order.mail_customer.unpaidbooking', $data)->render();
                    } else {
                        $template = view('dashboard.company.order.mail.unpaidbookingoffline', $data)->render();
                    }
                } elseif ($order->status == 1) {
                    $subject = "Booking for ".$company->company_name." #".$noInvoice;
                    if ($order->booking_type == 'online') {
                        $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                    } else {
                        $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                    }
                } elseif ($order->status == 2 || $order->status == 3) {
                    $subject = $company->company_name." Tour On Progress #".$noInvoice;
                    if ($order->booking_type == 'online') {
                        $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                    } else {
                        $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                    }
                } elseif ($order->status == 8) {
                    $subject = $company->company_name." New Booking Inquiry #".$noInvoice;
                    if ($order->booking_type == 'online') {
                        $template = view($company->active_theme->theme->source.'.booking-email', $data)->render();
                    } else {
                        $template = view($company->active_theme->theme->source.'.booking-offline-email',
                            $data)->render();
                    }
                } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
                    $subject = $company->company_name." #".$noInvoice." Booking Canceled";
                    if ($order->booking_type == 'online') {
                        $template = view('dashboard.company.order.mail_customer.cancelbooking', $data)->render();
                    } else {
                        $template = view('dashboard.company.order.mail.cancelbookingoffline', $data)->render();
                    }
                } else {
                    $subject = $company->company_name." Booking Completed #".$noInvoice;
                    if ($order->booking_type == 'online') {
                        $template = view($company->active_theme->theme->source.'.booking-email', $data)->render();
                    } else {
                        $template = view($company->active_theme->theme->source.'.booking-offline-email',
                            $data)->render();
                    }
                }

                dispatch(new SendEmail($subject, $to, $template));
            }
        }

    }
}
