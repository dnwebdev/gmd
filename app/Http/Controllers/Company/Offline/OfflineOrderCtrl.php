<?php

namespace App\Http\Controllers\Company\Offline;

use App\Jobs\SendEmail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OfflineOrderCtrl extends Controller
{

    // Api key xendit
    private $base_url = "https://api.xendit.co/v2/";
    private $xendit_key;
    use DiscordTrait;

    public function __construct()
    {
       $this->xendit_key = env('XENDIT_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.offline.index');
        }
        return view('dashboard.company.offline.index');
    }

    /**
     * function load datatables ajax
     *
     * @return void
     * @throws \Exception
     */
    public function loadAjaxData()
    {
        $company = auth('web')->user()->company;
        $order = Order::where('booking_type', 'offline')->where('id_company',
            $company->id_company)->with([
            'order_detail.product',
            'customer_info'
        ])->with('payment')->orderBy('created_at', 'desc');
        return \DataTables::of($order)
            ->addIndexColumn()
            ->editColumn('invoice_no', function ($model) {
                return '<a href="'.route('company.manual.detail',
                        ['id' => $model->invoice_no]).'">'.$model->invoice_no.'</a>';
            })
            ->editColumn('amount', function ($model) {
                return $model->order_detail->product->currency.' '.number_format($model->total_amount, 0);
            })
            ->editColumn('payment.expiry_date', function ($model) {
                if ($model->payment) {
                    return Carbon::parse($model->payment->expiry_date)->format('d M Y H:i:s');
                }
                return '-';
            })
//            ->editColumn('order_detail.product.product_name', function ($model) {
//                return '<a href="' . route('company.product.edit', ['id_product' => $model->order_detail->product->id_product]) . '">' . $model->order_detail->product->product_name . '</a>';
//            })
//            ->editColumn('order_detail.schedule_date', function ($model) {
//                return Carbon::parse($model->order_detail->schedule_date)->format('d M Y');
//            })
            ->editColumn('status', function ($model) {
                if ($model->status == '1') {
                    if ($model->payment->status == 'PENDING') {
                        return 'Waiting for Settlement';
                    }
                }
                if (isset($model->customer_manual_transfer->status) && $model->status == '0') {
                    return $model->listManualTransfer()[$model->customer_manual_transfer->status];
                } else{
                    return $model->status_text;
                }
                return $model->status_text;
            })
            ->addcolumn('action', function ($model) {
                if ($model->status == '7') {
                    return '<button class="btn btn-table btn-sm btn-warning btn-resend" data-id="'.$model->invoice_no.'">'.__('offline_invoice.resend.button_table').'</button>';
                }elseif($model->status =='0'){
                    return '<button class="btn btn-table btn-sm btn-danger btn-cancel-invoice px-2 py-1" data-id="'.$model->invoice_no.'">'.trans('order_provider.cancel').'</button>';
                }
            })
            ->rawColumns(['invoice_no', 'order_detail.product.product_name', 'action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $company = auth('web')->user()->company;
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.offline.create', compact('company'));
        }
        return view('dashboard.company.offline.create', compact('company'));
    }

    /**
     * function validation step one
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function stepOneValidation(Request $request)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $rules = [
            'invoice_name' => 'required|max:100',
            'expired_date' => 'required|date_format:Y-m-d|after_or_equal:'.$tomorrow,
            'details' => 'required|min:1|array',
            'details.*.description' => 'required|max:100',
            'details.*.price' => 'required|numeric|min:1',
            'details.*.qty' => 'required|numeric|min:1',
            'discount_name' => 'required_with:discount_nominal|max:100',
            'discount_amount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'nullable|numeric|min:1',

        ];
        if ($request->input('discount_amount_type') == 'percentage') {
            $rules['discount_amount'] .= '|max:100';
        }
        $this->validate($request, $rules);

        return apiResponse(200);
    }

    /**
     * function validation step two
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function stepTwoValidation(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function setOrder(Request $request)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $rules = [
            'invoice_name' => 'required|max:100',
            'expired_date' => 'required|date_format:Y-m-d|after_or_equal:'.$tomorrow,
            'details' => 'required|min:1|array',
            'details.*.description' => 'required|max:100',
            'details.*.price' => 'required|numeric|min:1',
            'details.*.qty' => 'required|numeric|min:1',
            'discount_name' => 'required_with:discount_nominal|max:100',
            'discount_amount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'nullable|numeric|min:1',
            'full_name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'nullable|numeric|digits_between:6,20',
            'address' => 'nullable|max:100',
            'country' => 'nullable|exists:tbl_country,id_country',
            'city' => 'nullable|exists:tbl_city,id_city',
        ];

        $this->validate($request, $rules);
        $unique = str_slug('OFFLINE '.date('YmdHis').' '.generateRandomString(4));
        while (Product::where('unique_code', $unique)->first()) {
            $unique = str_slug('OFFLINE '.date('YmdHis').' '.generateRandomString(4));
        }
        $totalOrigin = $total = 0;
        foreach ($request->input('details') as $detail) {
            $totalOrigin += (double)$detail['price'] * (int)$detail['qty'];
        }
        $discount = 0;
        if (checkRequestExists($request, 'discount_amount', 'POST') && checkRequestExists($request, 'discount_name',
                'POST')) {
            if ($request->input('discount_amount_type') == 'percentage') {
                $discount = (((double)$request->input('discount_amount') / 100) * $totalOrigin);
            } else {
                $discount = (double)$request->input('discount_amount');
            }
        }
        $total = $totalOrigin - $discount;
        if ($total < 10000) {
            return apiResponse(400, \trans('offline_invoice.validation.min_100'));
        }
        try {
            \DB::beginTransaction();
            $product = new Product();
            $product->id_company = auth('web')->user()->company->id_company;
            $product->id_product_type = 1;
            $product->product_name = $request->input('invoice_name');
            $product->unique_code = $unique;
            $product->brief_description = '-';
            $product->long_description = '-';
            $product->important_notes = $request->input('important_notes', null);
            $product->min_order = 1;
            $product->currency = 'IDR';
            $product->advertised_price = 0;
            $product->discount_amount = $request->input('discount_amount') ?? 0;
            $product->discount_amount_type = $request->input('discount_amount_type') == 'percentage' ? 1 : 0;
            $product->discount_name = $request->input('discount_name');
            $product->booking_type = 'offline';
            $product->created_by = auth('web')->user()->company->id_company;
            $product->updated_by = auth('web')->user()->company->id_company;
            $product->status = 1;
            $product->booking_confirmation = 1;
            $product->availability = 1;
            $product->publish = 0;
            $product->allow_credit_card = $request->input('allow_credit_card', 0);

            $product->save();
            $customer = Customer::where('email', $request->input('email'))->where('id_company',
                $request->get('my_company'))->first();
            if (!$customer) {
                $customer = Customer::create([
                    'id_company' => auth('web')->user()->company->id_company,
                    'first_name' => $request->get('full_name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone_number'),
                    'password' => bcrypt(str_random(12)),
                    'email_verified' => 1,
                    'phone_verified' => 1,
                    'status' => 1,
                    'language' => \Session::get('userLang') ?? 'en',
                ]);
                if (checkRequestExists($request, 'address', 'POST') && checkRequestExists($request, 'city', 'POST')) {


                    $address = \App\Models\CustomerAddress::create([
                        'id_customer' => $customer->id_customer,
                        'id_city' => $request->get('city'),
                        'address' => $request->get('address'),
                        'is_primary' => true,
                    ]);
                }
            } else {
                $customer->update([
                    'id_company' => auth('web')->user()->company->id_company,
                    'first_name' => $request->get('full_name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone_number'),
                    'email_verified' => 1,
                    'phone_verified' => 1,
                    'status' => 1,
                    'language' => \Session::get('userLang') ?? 'en',
                ]);
                if (checkRequestExists($request, 'address', 'POST') && checkRequestExists($request, 'city', 'POST')) {
                    $address = $customer->address()->where('is_primary', true)->first();
                    if ($address) {
                        $address->update([
                            'id_customer' => $customer->id_customer,
                            'id_city' => $request->get('city'),
                            'address' => $request->get('address'),
                            'is_primary' => true,
                        ]);
                    } else {
                        $address = \App\Models\CustomerAddress::create([
                            'id_customer' => $customer->id_customer,
                            'id_city' => $request->get('city'),
                            'address' => $request->get('address'),
                            'is_primary' => true,
                        ]);
                    }
                }
            }


            $invoice_no = 'OFINV'.date('ymd').rand(100000, 999999);
            while ($check = Order::where('invoice_no', $invoice_no)->first()) {
                $invoice_no = 'OFINV'.date('ymd').rand(100000, 999999);
            }
            if ($request->input('payment_method') == 'credit_card') {
                $fee_credit_card = ceil(((100 / 97.1) * $total) - $total);
            } else {
                $fee_credit_card = 0;

            }
            $grandTotal = $total + $fee_credit_card;
            $dataOrder = [
                'invoice_no' => $invoice_no,
                'id_company' => auth('web')->user()->company->id_company,
                'transaction_type' => 0,
                'id_customer' => $customer ? $customer->id_customer : null,
                'id_user_agen' => null,
                'product_discount' => $discount,
                'amount' => $totalOrigin,
                'fee_credit_card' => $fee_credit_card,
                'fee' => 0,
                'total_amount' => (int)ceil($total),
                'status' => 0,
                'is_void' => false,
                'external_notes' => $request->input('important_notes'),
                'internal_notes' => null,
                'id_voucher' => null,
                'voucher_type' => null,
                'voucher_amount_type' => null,
                'voucher_code' => null,
                'voucher_description' => null,
                'voucher_amount' => 0,
                'allow_payment' => 1,
                'booking_type' => 'offline',
                'invoice_detail' => $request->input('details')

            ];
            $order = Order::create($dataOrder);
            $oldPath = 'img/no-product-image.png';
            if (!\File::exists(public_path($oldPath))) {
                $oldPath = public_path('img/img2.jpg');
            }

            $fileExtension = \File::extension($oldPath);
            $newName = $invoice_no.'-'.$product->unique_code.'.'.$fileExtension;
            $newPathWithName = 'uploads/orders/'.$newName;
            if (!\File::isDirectory('uploads/orders')):
                \File::makeDirectory('uploads/orders', 777, true, true);
            endif;
            $rate = 1;
            \File::copy($oldPath, $newPathWithName);
            $dataDetail = [
                'invoice_no' => $invoice_no,
                'schedule_date' => Carbon::now()->toDateString(),
                'id_product' => $product->id_product,
                'id_product_type' => $product->id_product_type,
                'id_product_category' => $product->id_product_category,
                'product_name' => $product->product_name,
                'product_description' => $product->brief_description,
                'long_description' => $product->long_description,
                'itinerary' => $product->itinerary ? $product->itinerary : null,
                'duration' => $product->duration,
                'duration_type' => $product->duration_type,
                'id_city' => $product->id_city,
                'currency' => $product->currency,
                'rate' => $rate,
                'product_total_price' => $grandTotal,
                'adult' => 1,
                'children' => 0,
                'infant' => 0,
                'unit_name_id' => null,
                'main_image' => $newName,
                'product_total_tax' => 0,
                'fee_name' => $product->fee_name,
                'fee_amount' => $product->fee_amount ?? 0,
                'discount_name' => $product->discount_name,
                'discount_amount_type' => $product->discount_amount_type,
                'discount_amount' => $product->discount_amount,
                'notes' => $request->get('note'),
                'adult_price' => 1,
                'children_price' => 0,
                'infant_price' => 0,

            ];
            $detail = \App\Models\OrderDetail::create($dataDetail);
            $detailCustomer = [
                'invoice_no' => $invoice_no,
                'person_type' => 1,
                'first_name' => $customer->first_name,
                'last_name' => null,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'id_city' => $request->get('city'),
                'address' => $request->get('address'),
                'identity_number' => $request->get('identity_number'),
                'identity_number_type' => $request->get('identity_number_type'),
                'emergency_number' => $request->get('emergency_number'),
            ];
            \App\Models\OrderCustomer::create($detailCustomer);
//            $dataXendit = [
//                "external_id" => $order->invoice_no,
//                "amount" => $order->total_amount,
//                "payer_email" => $order->customer_info->email,
//                "description" => "INVOICE #" . $order->invoice_no . ' - ' . $order->order_detail->product_name,
//                "invoice_duration"=>Carbon::now()->diffInSeconds(Carbon::parse($request->expired_date.' '.date('H:i:s')))
//            ];
//            $data_string = json_encode($dataXendit);
//            $url = $this->base_url . 'invoices';
//            $res = json_decode($this->post_curl($url, $data_string));
//            if (isset($res->error_code)) {
//                \DB::rollBack();
//                return response()->json(['message' => $res->message], 403);
//            }
//            $payment = \App\Models\Payment::create([
//                'invoice_no' => $invoice_no,
//                'payment_gateway' => $request->input('payment_method') == 'credit_card' ? 'Xendit Credit Card' : 'Xendit Virtual Account',
//                'reference_number' => $res->id,
//                'status' => $res->status,
//                'amount' => $res->amount,
//                'invoice_url' => $res->invoice_url,
//                'expiry_date' => date('Y-m-d H:i:s', strtotime($res->expiry_date)),
//                'created_at' => date('Y-m-d H:i:s', strtotime($res->created)),
//                'updated_at' => date('Y-m-d H:i:s', strtotime($res->updated)),
//                'response' => $res,
//            ]);

            $payment = \App\Models\Payment::create([
                'invoice_no' => $invoice_no,
                'payment_gateway' => $request->input('payment_method') == 'credit_card' ? 'Xendit Credit Card' : 'Xendit Virtual Account',
                'reference_number' => null,
                'status' => null,
                'amount' => null,
                'invoice_url' => null,
                'expiry_date' => $request->expired_date.' '.date('H:i:s'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'response' => null,
            ]);

            \DB::commit();
            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer(auth('web')->user()->company->id_company, $invoice_no);
            $product_service->sendWACustomer($invoice_no);
            $product_service->allMailProvider(auth('web')->user()->company->id_company, $invoice_no);
            $product_service->sendWAProvider( $invoice_no);
            \Log::info('WAProvider from'.OfflineOrderCtrl::class.' line 439');
//            $this->send_invoice(auth('web')->user()->company->id_company, $invoice_no);
//            $this->send_email_notif(auth('web')->user()->company->id_company, $invoice_no);
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = auth('web')->user()->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New E-Invoice '.Carbon::now()->format('d M Y H:i:s').'**';
            $content .= '```';
            $content .= "Company Name    : ".$newCompany->company_name."\n";
            $content .= "Domain Gomodo   : ".$http.$newCompany->domain_memoria."\n";
            $content .= "Email Company   : ".$newCompany->email_company."\n";
            $content .= "Phone Number    : ".$newCompany->phone_company."\n";
            $content .= "Invoice Name    : ".$order->order_detail->product_name."\n";
            $content .= "Customer Name   : ".$order->customer_info->first_name."\n";
            $content .= "Customer Email  : ".$order->customer_info->email."\n";
            $content .= "Total Nominal   : ".format_priceID($order->total_amount)."\n";
//            $content .= "Payment Method  : " . $order->payment->payment_gateway. "\n";
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
            $this->sendDiscordNotification(sprintf('%s', $content), 'transaction');
            return response()->json([
                'status' => 200,
                'message' => 'New Order has been Created',
                'data' => [
                    'invoice' => $invoice_no,
                    'url' => route('invoice.bank-transfer', ['invoice' => $invoice_no])
                ]
            ]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    /**
     * function get detail data
     *
     * @param  mixed  $id
     *
     * @return void
     */
    public function detail($id)
    {
        $order = \App\Models\Order::where(
            [
                'invoice_no' => $id,
                'id_company' => auth('web')->user()->id_company,
            ])->where('booking_type', 'offline')->first();
        if (!$order) {
            msg(trans('general.not_found'), 2);
            return redirect()->route('company.manual.index');
        }
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.offline.detail', compact('order'));
        }
        return view('dashboard.company.offline.detail', compact('order'));
    }

    /**
     * function load post curl xendit
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
     * function send invoice
     *
     * @param  mixed  $company
     * @param  mixed  $id
     *
     * @return void
     */
    public function send_invoice($company, $id)
    {
        //$this->company= $request->get('my_company') ? $request->get('my_company') : 0;

        $company = \App\Models\Company::find($company);
        $order = \App\Models\Order::find($id);


        $attached = [];
//        if ($order->status == 1) {
//            //Make PDF INVOICE
//            // $pdf_view = view($company->active_theme->theme->source.'.invoicepdf',['company'=>$company,'order'=>$order])->render();
//            // //$pdf_view = $company->active_theme->theme->source;
//            // $pdf = $utility->make_pdf($pdf_view);
//            // $attached = ['data'=>$pdf,'name'=>$order->invoice_no.'-'.$order->customer_info->first_name.'.pdf'];
//
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

        $subject = null;
        $to = $order->customer_info->email;
        $pdf = null;

        //Send Mail INVOICE
        if ($order->status == 0) {
            $subject = "Order Invoice & Itinerary for ".$company->company_name;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.unpaidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.unpaidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfUnpaidBookingOffline';
            }
        } elseif ($order->status == 1) {
            $subject = "Booking for ".$company->company_name." #".$id;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 2 || $order->status == 3) {
            $subject = $company->company_name." Tour On Progress #".$id;
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.paidbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = view('dashboard.company.order.mail.paidbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 8) {
            $subject = $company->company_name." New Booking Inquiry #".$id;
            if ($order->booking_type == 'online') {
                $template = view($company->active_theme->theme->source.'.booking-email', $data)->render();
                $pdf = $company->active_theme->theme->source.'.booking-email';
            } else {
                $template = view($company->active_theme->theme->source.'.booking-offline-email', $data)->render();
                $pdf = $company->active_theme->theme->source.'.booking-offline-email';
            }
        } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
            $subject = $company->company_name." #".$id." Booking Canceled";
            if ($order->booking_type == 'online') {
                $template = view('dashboard.company.order.mail_customer.cancelbooking', $data)->render();
                $pdf = 'dashboard.company.order.mail_customer.pdfCancelBooking';
            } else {
                $template = view('dashboard.company.order.mail.cancelbookingoffline', $data)->render();
                $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
            }
        } else {
            $subject = $company->company_name." Booking Completed #".$id;
            if ($order->booking_type == 'online') {
                $template = view($company->active_theme->theme->source.'.booking-email', $data)->render();
                $pdf = $company->active_theme->theme->source.'.booking-email';
            } else {
                $template = view($company->active_theme->theme->source.'.booking-offline-email', $data)->render();
                $pdf = $company->active_theme->theme->source.'.booking-offline-email';
            }
        }
//        Log::info($company);

//        $template = view($company->active_theme->theme->source . '.booking-email', $data)->render();

        dispatch(new SendEmail($subject, $to, $template, $pdf, $data));
    }

    /**
     * function send email notification order
     *
     * @param  mixed  $company
     * @param  mixed  $id
     *
     * @return void
     */
    public function send_email_notif($company, $id)
    {
        $company = \App\Models\Company::find($company);
        $order = \App\Models\Order::find($id);
        $email_view_data = ['company' => $company, 'order' => $order];

        $to = $order->company->email_company;
        $template = null;
        $subject = null;
        $pdf = null;
        if (!empty($company->email_company) && $order) {
            if ($order->status == 1) {
                $subject = "New Confirmed Booking #".$id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.paidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 0) {
                $subject = "Unpaid Booking #".$id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.unpaidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.unpaidprovider', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfUnpaidProvider';
                }
            } elseif ($order->status == 2 || $order->status == 3) {
                $subject = "Tour On Process #".$id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.paidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 8) {
                $subject = "New Booking Inquiry ".$id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.emailnotif', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotif';
                } else {
                    $template = view('dashboard.company.order.emailnotiforderoffline', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.emailnotiforderoffline';
                }
            } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
                $subject = "#".$id." Booking Canceled";
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.cancelprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfCancelProvider';
                } else {
                    $template = view('dashboard.company.order.mail.cancelbookingoffline', $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
                }
            } else {
                $subject = "#".$id." Booking Complete!";
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

    public function resendInvoice(Request $request)
    {
        $rule = [
            'id' => [
                'required',
                Rule::exists('tbl_order_header', 'invoice_no')
                    ->where('id_company', auth('web')->user()->id_company)
                    ->where('booking_type', 'offline')
            ]
        ];

        $this->validate($request, $rule);

        $order = Order::find($request->input('id'));
        if ($order->status!= '7'){
            return apiResponse(400,trans('offline_invoice.resend.cannot_process'));
        }
        $payment = $order->payment;
        \Xendit::makeExpiredInvoice($order->invoice_no)->send();
        $originAmount = $order->total_amount-$order->fee - $order->fee_credit_card;
        $order->update([
            'total_amount'=>$originAmount,
            'fee'=>0,
            'fee_credit_card'=>0,
        ]);

        $payment->update([
            'reference_number' => null,
            'status' => 'PENDING',
            'payment_gateway'=>'Xendit Virtual Account',
            'expiry_date' => Carbon::now()->addDay()->toDateTimeString(),
            'invoice_url' => null,
            'response' => null
        ]);

        $order->update(['status' => 0]);
        $product_service = new ProductService();
        $product_service->allMailCustomer(auth('web')->user()->company->id_company, $order->invoice_no);
        $product_service->sendWACustomer( $order->invoice_no);
        $product_service->allMailProvider(auth('web')->user()->company->id_company, $order->invoice_no);
        $product_service->sendWAProvider( $order->invoice_no);
        \Log::info('WAProvider from'.OfflineOrderCtrl::class.' line 776');
        return apiResponse(200, __('general.success'), compact('payment', 'order'));
    }
}
