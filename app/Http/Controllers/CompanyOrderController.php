<?php

namespace App\Http\Controllers;

use App\Enums\CustomerManualTransferStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomDetailExport;
use App\Models\CustomDetail;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Order;
use App\Traits\DiscordTrait;
use Carbon\Carbon;

class CompanyOrderController extends Controller
{
    use DiscordTrait;

    var $company = 0;

    /**
     * __construct
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth')->except('validate_schedule');
    }

    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initalize(Request $request)
    {
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        $request['my_company'] = $this->company;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initalize($request);
        $order = \App\Models\Order::where('id_company', $this->company)->orderBy('created_at', 'DESC')->paginate(10);
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.order.index', ['order' => $order,]);
        }
        return view('dashboard.company.order.index', ['order' => $order,]);
    }

    /**
     * function load more data
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function load_more(Request $request)
    {
        $this->initalize($request);

        $offset = !empty($request->segment(4)) ? $request->segment(4) : 0;
        $order = \App\Models\Order::where('id_company', $this->company)->skip($offset)->take(20)->orderBy('created_at', 'DESC')->get();

        if (auth()->user()->company->is_klhk == 1) {
            $view = view('klhk.dashboard.company.order.search', ['order' => $order,])->render();
        }
        $view = view('dashboard.company.order.search', ['order' => $order,])->render();

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'data' => [
                'view' => $view, 'offset' => ($offset + $order->count())
            ]
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(Request $request)
    {
        $product_type = \App\Models\ProductType::get();
        $list_status = \App\Models\Order::list_status();
        $category = \App\Models\ProductCategory::where('status', 1)->get();
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.order.create', [
                'product_type' => $product_type,
                'list_status' => $list_status,
                'product_category' => $category,
            ]);
        }

        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.order.create', [
                'product_type' => $product_type,
                'list_status' => $list_status,
                'product_category' => $category,
            ]);
        }
        return view('dashboard.company.order.create', [
            'product_type' => $product_type,
            'list_status' => $list_status,
            'product_category' => $category,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    //public function store(\App\Http\Requests\CreateOrderFormRequest $request)
    public function store(Request $request)
    {
        $this->initalize($request);
        $product_service = app('\App\Services\ProductService');
        $request->request->set('agent', $this->user);
        $request->request->set('transaction_type', 1);
        $request['transaction_type'] = 1;

        $send_invoice = $request->get('send_invoice');
        $send_inv = false;
        if ($send_invoice == 'yes') {
            $send_inv = true;
        }
        $order = json_decode($product_service->make_order($send_inv));
        if ($order->status == 200) {
            $status = $request->get('status');

            if ($status == 1) {
                $or = \App\Models\Order::find($order->data->invoice);

                $journal = app('App\Services\JournalService');
                $journal->add([
                    'id_company' => $or->id_company,
                    'journal_code' => $or->invoice_no,
                    'journal_type' => 100,
                    'description' => 'Order Product : ' . $or->order_detail->product_name,
                    'currency' => $or->order_detail->currency,
                    'rate' => $or->order_detail->rate,
                    'amount' => $or->total_amount,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'New Order has been Created : ' . $order->data->invoice,
                'data' => ['url' => Route('company.order.edit', $order->data->invoice)]
            ]);
        } else {
            return response()->json($order);
        }
    }


    /**
     * function get total amount
     *
     * @return void
     */
    public function get_total_amount()
    {
        $request = app('\App\Http\Requests\GetPriceFormRequest');

        $this->initalize($request);
        $product_service = app('\App\Services\ProductService');

        return $product_service->get_total_amount($this->company);
    }


    /**
     * function validate schedule data
     *
     * @return void
     */
    public function validate_schedule()
    {
        $request = app('\App\Http\Requests\ValidateSchedule');

        $this->initalize($request);
        $product_service = app('\App\Services\ProductService');

        return $product_service->validate_schedule($this->company);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $this->initalize($request);
        $invoice_no = $request->segment(3);
        $order = \App\Models\Order::where([
            'invoice_no' => $invoice_no,
            'id_company' => auth('web')->user()->id_company
        ])->with('order_detail.customDetail')->firstOrFail();

        switch (optional($order->payment)->payment_gateway) {
            case 'Xendit Virtual Account':
                $code_payment = 'virtual_account';
                break;
            case 'Xendit Credit Card':
                $code_payment = 'credit_card';
                break;
            case 'Xendit Virtual Account OVO':
                $code_payment = 'ovo';
                break;
            case 'Xendit Alfamart':
                $code_payment = 'alfamart';
                break;
            case 'Xendit Dana':
                $code_payment = 'dana';
                break;
            case 'Xendit LinkAja':
                $code_payment = 'linkaja';
                break;
            case 'Midtrans Indomaret':
                $code_payment = 'indomaret';
                break;
            case 'Midtrans Gopay':
                $code_payment = 'gopay';
                break;
            case 'Xendit Kredivo':
                $code_payment = 'kredivo';
                break;
            case 'Xendit OVO':
                $code_payment = 'ovo_live';
                break;
            case 'Midtrans Virtual Account BCA':
                $code_payment = 'bca_va';
                break;
            case 'Midtrans AkuLaku':
                $code_payment = 'akulaku';
                break;
            case 'Manual Transfer BCA':
                $code_payment = 'bca_manual';
                break;
            case 'Cash On Delivery':
                $code_payment = 'cod';
                break;
            default:
                $code_payment = 'redeem';
                break;
        }

        $payment = $order->company->payments()->where('code_payment', $code_payment)->first();

        if (!$order) {
            msg('Order not found', 2);
            return redirect()->route('company.order.index');
        }
        $order->update(['is_read' => 1]);
        $product_type = \App\Models\ProductType::get();

        // Jika custom info tidak kosong
        // Kita lookup di awal untuk menghindari N+1
        $location = $this->lookupLocation($order->order_detail->customDetail);

        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.order.edit', [ //'extra' => $extra,
                'product_type' => $product_type,
                'order' => $order,
                'location' => $location
            ]);
        }
        return view('dashboard.company.order.edit', [ //'extra' => $extra,
            'product_type' => $product_type,
            'order' => $order,
            'location' => $location,
            'payment' => $payment
        ]);
    }

    /**
     * Update status the specified resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function editStatus(Request $request)
    {
        $rule = [
            'status' => 'required|in:0,1,6'
        ];
        $this->validate($request, $rule);
        $invoice_no = $request->segment(3);
        $order = \App\Models\Order::where(
            [
                'invoice_no' => $invoice_no,
                'id_company' => auth('web')->user()->id_company,
            ]
        )->whereHas('payment', function ($pa) {
            $pa->where('payment_gateway', 'Cash On Delivery');
        })->first();
        if (!$order) {
            return abort(404);
        }
        $status = $order->status;
        if ($status == '1') {
            $order->payment->update(['status' => 'PAID']);
        } elseif ($status == '0') {
            $order->payment->update(['status' => 'PENDING']);
        } else {
            $order->payment->update(['status' => 'FAILED']);
        }
        $order->update(['status' => $request->input('status')]);
        if ($status != $order->status) {
            $utility = app('\App\Services\ProductService');
            $utility->allMailCustomer($order->id_company, $invoice_no);
            $utility->sendWACustomer( $invoice_no);
        }

        return redirect()->route('company.order.index');
    }

    public function editManualTransfer(Request $request)
    {
        $rule = [
            'note_customer' => 'required',
            'status' => 'required',
        ];

        $this->validate($request, $rule);

        $invoice_no = $request->segment(3);
        $order = Order::where(['invoice_no' => $invoice_no, 'id_company' => auth('web')->user()->id_company])
            ->whereHas('payment', function ($q) {
                $q->where('payment_gateway', 'Manual Transfer BCA');
            })->first();
        if (!$order) {
            return abort(404);
        }
        if (!empty($order->customer_manual_transfer) && in_array(optional($order->customer_manual_transfer)->status, ['rejected','accept','rejected_reupload'])) {
            return apiResponse(403, \trans('order_provider.status_manualtransfer.error_status'), ['redirect' => route('company.order.edit', ['invoice_no' => $order->invoice_no])]);
        }
        // $data = [
        //     'order' => $order,
        //     'company' => $order->company,
        // ];
        switch ($request->status) {
            case 'accept':
                // // $subject = 'Selamat Telah diterima';
                // $template = view('mail.manual-transfer.accept', $data)->render();
                $order->update(['status' => 1]);
                $order->payment->update(['status' => 'PAID']);
                $order->customer_manual_transfer->update([
                    'note_customer' => $request->note_customer,
                    'status' => CustomerManualTransferStatus::StatusAccept
                ]);
            break;
            case 'rejected_reupload':
                // $subject = 'Bukti pembayaran di tolak silahkan upload ulang';
                // $template = view('mail.manual-transfer.reupload', $data)->render();
                // $order->update(['status' => 8]);
                $order->payment->update([
                    'expiry_date' => now()->addHours(24)->toDateTimeString()
                ]);
                $order->customer_manual_transfer->update([
                    'note_customer' => $request->note_customer,
                    'status' => CustomerManualTransferStatus::StatusRejectReupload
                ]);
            break;
            case 'rejected':
                // $subject = 'Bukti pembayaran di tolak';
                // $template = view('mail.manual-transfer.reject', $data)->render();
                $order->update(['status' => 6]);
                $order->payment->update(['status' => 'CANCEL BY VENDOR']);
                $order->customer_manual_transfer->update([
                    'note_customer' => $request->note_customer,
                    'status' => CustomerManualTransferStatus::StatusReject
                ]);
                break;
        }

        // Send Mail to Customer
        // dispatch(new SendEmail($subject, $order->customer_info->email, $template));
        $sendEMail = app('\App\Services\ProductService');
        $sendEMail->allMailCustomer($order->id_company, $invoice_no);
        $sendEMail->sendWACustomer( $order->invoice_no);

        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
        $newCompany = $order->company;
        $loc = \Stevebauman\Location\Facades\Location::get($ip);
        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
        $content = '**New Provider Confirmation to Customer Status :'.$order->listManualTransfer()[$request->status].' '. Carbon::now()->format('d M Y H:i:s').'**';
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
        $content .= "Check Order     : ".$http.env('APP_URL').'/back-office/transaction-manual/'.$order->invoice_no.'/detail'."\n";
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

        return apiResponse(200, 'Ubah status berhasil', ['redirect' => route('company.order.edit', ['invoice_no' => $invoice_no])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function update(\App\Http\Requests\CreateOrderFormRequest $request)
    {
        $this->initalize($request);
        $invoice_no = $request->segment(3);


        $order = \App\Models\Order::where(['invoice_no' => $invoice_no, 'id_company' => $this->company])->first();
        if (!$order) {
            return json_encode(['status' => 404, 'message' => 'Order is not valid' . $invoice_no]);
        }

        $request->request->set('transaction_type', 1);

        $product_service = app('\App\Services\ProductService');

        $pricing = json_decode($product_service->get_total_amount($this->company));
        if ($pricing->status != 200) {
            return json_encode($pricing);
        }

        $customer = $product_service->check_customer($request);

        \DB::transaction(function () use ($order, $pricing, $customer, $request) {


            $pricing = $pricing->data;
            $transaction_type = 1;


            $product = $request->get('product');

            $product = \App\Models\Product::where(['id_company' => $this->company, 'id_product' => $product])->first();

            $schedule = $request->get('schedule');
            $voucher_code = $request->get('voucher_code');
            $status = $request->get('status');
            $external_notes = $request->get('external_notes');
            $internal_notes = $request->get('internal_notes');

            $adult = $request->get('adult');
            $children = $request->get('children');
            $infant = $request->get('infant');
            $qty = $request->get('qty');
            $rate = 1;

            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $passport = $request->get('passport');
            $city = $request->get('city');
            $address = $request->get('address');

            $custno = $customer ? $customer->id_customer : 0;

            $allow_payment = 1;
            if ($product->booking_confirmation == 0) {
                $allow_payment = $request->get('allow_payment') ? $request->get('allow_payment') : 0;
            }


            $data_order_header = [
                'transaction_type' => $transaction_type,
                'id_customer' => $customer ? $customer->id_customer : null,
                'id_user_agen' => $transaction_type ? $this->user : null,
                'product_discount' => $pricing->total_discount,
                'amount' => $pricing->amount,
                'fee' => $pricing->total_fee,
                'total_amount' => $pricing->total_amount,
                'status' => $status,
                'external_notes' => $external_notes,
                'internal_notes' => $internal_notes,
                'allow_payment' => $allow_payment,
            ];

            if ($status == 99) {
                $data_order_header['is_void'] = true;
                $data_order_header['void_reason'] = $request->get('void_reason') ? $request->get('void_reason') : null;
            }


            if (!empty($voucher_code)) {
                $voucher = \App\Models\Voucher::where([
                    'voucher_code' => $voucher_code, 'id_company' => $this->company, 'status' => 1
                ])->first();
                if (!$voucher) {
                    return json_encode(['status' => 404, 'message' => 'Voucher not found']);
                }

                $voucher_has_use = \App\Models\Order::where([
                    ['id_company', '=', $this->company],
                    ['id_customer', '=', $custno],
                    ['id_voucher', '=', $voucher->id_voucher],
                    ['status', '<', 5]
                ])->count();

                //Belom pernah pakai Voucher
                if ($voucher_has_use == 0) {
                    if ($voucher->voucher_type == 1 || $voucher->voucher_type == 0 && $voucher->id_customer == $custno) {
                        $data_voucher = [
                            'id_voucher' => $voucher->id_voucher,
                            'voucher_type' => $voucher->voucher_type,
                            'voucher_amount_type' => $voucher->voucher_amount_type,
                            'voucher_code' => $voucher->voucher_code,
                            'voucher_description' => $voucher->voucher_description,
                            'voucher_amount' => $voucher->voucher_amount,
                        ];

                        $data_order_header = array_merge($data_order_header, $data_voucher);

                        //Update Voucher
                        if ($voucher->voucher_type == 0) {
                            $voucher->status = 0;
                            $voucher->save();
                        } else {
                            $used = \App\Models\Order::where([
                                ['id_company', '=', $this->company],
                                ['id_voucher', '=', $voucher->id_voucher],
                                ['status', '<', 5]
                            ])->count();
                            if ($used == ($voucher->max_use - 1)) {
                                $voucher->status = 0;
                                $voucher->save();
                            }
                        }
                    }
                }
            }


            \App\Models\Order::where([
                'id_company' => $this->company, 'invoice_no' => $order->invoice_no
            ])
                ->update($data_order_header);

            $request_extra = $request->get('extra');
            if (!empty($request_extra)) {
                $extra_qty = $request->get('extra_qty');
                $extra = \App\Models\ExtraItem::whereIn('id_extra', $request_extra)->get();
                if ($extra) {
                    $data_order_extra = [];
                    foreach ($extra as $row) {
                        foreach ($request->get('extra') as $k => $ext) {
                            if ($ext == $row->id_extra) {
                                array_push($data_order_extra, [
                                    'invoice_no' => $order->invoice_no,
                                    'id_extra' => $row->id_extra,
                                    'extra_name' => $row->extra_name,
                                    'description' => $row->description,
                                    'currency' => $row->currency,
                                    'amount' => $row->amount,
                                    'qty' => $extra_qty[$k],
                                    'rate' => $rate,
                                    'extra_price_type' => $row->extra_price_type,
                                ]);
                            }
                        }
                    }
                    \App\Models\OrderExtraItem::where(['invoice_no' => $order->invoice_no])->delete();
                    \App\Models\OrderExtraItem::insert($data_order_extra);
                }
            }

            if ($status == 1) {
                $journal = app('App\Services\JournalService');
                $journal->add([
                    'id_company' => $order->id_company,
                    'journal_code' => $order->invoice_no,
                    'journal_type' => 100,
                    'description' => 'Order Product : ' . $order->order_detail->product_name,
                    'currency' => $order->order_detail->currency,
                    'rate' => $order->order_detail->rate,
                    'amount' => $pricing->total_amount,
                ]);
            } else if ($status == 99) {
                $journal = app('App\Services\JournalService');
                $journal->add([
                    'id_company' => $order->id_company,
                    'journal_code' => $order->invoice_no,
                    'journal_type' => 900,
                    'description' => 'Void Order : ' . $order->invoice_no,
                    'currency' => $order->order_detail->currency,
                    'rate' => $order->order_detail->rate,
                    'amount' => -$pricing->total_amount,
                ]);
            }
        });

        $send_invoice = $request->get('send_invoice');

        if ($send_invoice == 'yes') {
            $email = $request->get('email');

            $utility = app('\App\Services\ProductService');
            $utility->allMailCustomer($order->id_company, $invoice_no);
            $utility->sendWACustomer($invoice_no);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Invoice Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    public function exportCustomDetail($invoice_no)
    {
        return Excel::download(new CustomDetailExport($invoice_no), 'Detail ' . $invoice_no . '.xlsx');
    }

    public function downloadDetailAttachment($invoice_no)
    {
        $zip = new \ZipArchive;
        $temp_file = tempnam("/tmp", "zip");

        $details = CustomDetail::select('label_name', 'participant', 'value')
            ->whereIn('type_custom', ['photo', 'document'])
            ->whereHas('orderDetail', function ($query) use ($invoice_no) {
                return $query->where('invoice_no', $invoice_no);
            })->get();

        $zip->open($temp_file, \ZipArchive::CREATE);

        foreach ($details as $detail) {
            $file = storage_path('app/' . $detail->getOriginal('value'));
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $new_name = $detail->participant == 0 ? trans('customer.book.customer') : trans('customer.book.participant') . ' ' . $detail->participant;
            $new_name .= ' - ' . $detail->label_name;
            $new_name .= '.' . $ext;
            $zip->addFile($file, $new_name);
        }

        $zip->close();

        return response()->download($temp_file, 'Additional Info - ' . $invoice_no . '.zip')->deleteFileAfterSend(true);
    }

    protected function lookupLocation($model)
    {
        // Jika custom info tidak kosong
        // Kita lookup di awal untuk menghindari N+1
        $location = [];
        if (!empty($model)) {
            // Jika type negara tidak kosong maka lookup data negara
            if ($model->where('type_custom', 'country')->count() > 0) {
                $countries = $model->where('type_custom', 'country')->pluck('value')->toArray();
                $location['country'] = Country::select('id_country', 'country_name')
                    ->whereIn('id_country', $countries)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->id_country => $item->country_name];
                    });
            }

            // Jika type state tidak kosong maka lookup dulu
            if ($model->where('type_custom', 'state')->count() > 0) {
                $states = $model->where('type_custom', 'state')->pluck('value')->toArray();
                $location['state'] = State::select('id_state', 'state_name', 'id_country')
                    ->whereIn('id_state', $states)
                    ->with('country:id_country,country_name')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id_state => $item->state_name . ', ' . $item->country->country_name
                        ];
                    });
            }

            if ($model->where('type_custom', 'city')->count() > 0) {
                $cities = $model->where('type_custom', 'city')->pluck('value')->toArray();
                $location['city'] = City::select('id_city', 'city_name', 'id_state')
                    ->whereIn('id_city', $cities)
                    ->with([
                        'state:id_state,state_name,id_country',
                        'state.country:id_country,country_name'
                    ])
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id_city => $item->city_name . ', ' . $item->state->state_name . ', ' . $item->state->country->country_name
                        ];
                    });
            }
        }

        return $location;
    }
}
