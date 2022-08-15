<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\HomeCtrl;
use App\Models\CompanyPayment;
use App\Models\Customer;
use App\Models\CustomSchema;
use App\Models\ListPayment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use App\Traits\CustomInfoRulesTrait;
use App\Traits\DiscordTrait;
use App\Traits\InsuranceValidationTrait;
use App\Traits\ValidationScheduleTrait;
use Carbon\Carbon;
use Gomodo\Midtrans\MidTrans;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use ValidationScheduleTrait, DiscordTrait, CustomInfoRulesTrait, InsuranceValidationTrait;

    private $base_url = "https://api.xendit.co/v2/";
    private $xendit_key;

    /**
     * HomeCtrl constructor.
     */
    public function __construct()
    {
        $this->middleware('company');
        $this->xendit_key = env("XENDIT_KEY");
    }

    public function pay(Request $request)
    {
        if ($request->has('booking_type') && $request->booking_type == 'offline') {
            return $this->payOffline($request);
        }
        $ruleInsurance = [
            'rule' => [],
            'attributes' => []
        ];
        $this->customRuleRefill($request, $request->input('product'));
        if ($request->has('use_insurance') && count($request->get('use_insurance')) == '1') {
            $ruleInsurance = $this->validationInsurance($request, array_keys($request->get('use_insurance'))[0]);
//            dd($ruleInsurance);
        }

        $rule = [
            'sku' => [
                'required',
                Rule::exists('tbl_product', 'unique_code')->where('id_company',
                    auth('api')->user()->company->id_company)->where('status', 1)
            ],
            'pax' => 'required|numeric|min:0',
            'departure_date' => 'required|date_format:Y-m-d',
            'full_name' => 'nullable|max:100',
            'phone_number' => 'nullable|numeric|digits_between:6,20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|max:100',
            'country' => 'nullable|exists:tbl_country,id_country',
            'city' => 'required_with:address|exists:tbl_city,id_city',
            'identity_number_type' => 'nullable|in:ktp,passport',
            'emergency_number' => 'nullable|numeric|digits_between:6,20',
            'payment_method' => 'required'
        ];

        $attributes = [];
        if ($request->input('payment_method') == 'kredivo') {
            $rule = array_merge($rule, [
                'kredivo_first_name' => 'required',
                'kredivo_last_name' => 'required',
                'kredivo_phone_number' => 'required|numeric|digits_between:6,20',
                'kredivo_email' => 'required|email',
                'kredivo_address' => 'required',
                'kredivo_state' => 'required|exists:tbl_state,id_state',
                'kredivo_city' => 'required|exists:tbl_city,id_city',
                'kredivo_postal_code' => 'required|numeric|min:5',
                'kredivo_installment_duration' => [
                    'required',
                    Rule::in(array_keys(\App\Models\PaymentKredivo::$durations))
                ]
            ]);

            $attributes = collect(trans('customer.kredivo.form'))
                ->mapWithKeys(function ($value, $key) {
                    return ['kredivo_' . $key => strtolower($value)];
                })
                ->toArray();
        }

        $message = [
            'full_name.required' => \trans('customer.book_validation.full_name'),
            'email.required' => \trans('customer.book_validation.email'),
            'email.email' => \trans('customer.book_validation.email_correction'),
            'identity_number.required' => \trans('customer.book_validation.identity_number'),
            'identity_number.digits' => \trans('customer.book_validation.identity_number_digits'),
            'identity_number.min' => \trans('customer.book_validation.identity_number_min'),
            'identity_number.max' => \trans('customer.book_validation.identity_number_max'),
            'country.required' => \trans('customer.book_validation.country'),
            'phone_number.phone' => \trans('customer.book_validation.phone_number'),
            'address.required' => \trans('customer.book_validation.address'),
            'emergency_number.required' => \trans('customer.book_validation.emergency_number'),
            'emergency_number.numeric' => \trans('customer.book_validation.emergency_number_numeric'),
            'city.required' => \trans('customer.book_validation.city'),
            'payment_list.required' => \trans('customer.book_validation.payment_method'),
        ];

        if (checkRequestExists($request, 'identity_number_type', 'post')) {
            if ($request->input('identity_number_type') === 'ktp') {
                $rule['identity_number'] = 'nullable|digits:16';
            } elseif ($request->input('identity_number_type') === 'passport') {
                $rule['identity_number'] = 'nullable|min:8|max:20';
            }
        }

        // Start Custom information rules
        $custom_rules = $this->generateCustomRules($request->input('sku'));
        $rule = array_merge($rule, $custom_rules['rules']);
        $rule = array_merge($rule, $ruleInsurance['rule']);
        $attributes = array_merge($custom_rules['attributes'], $ruleInsurance['attributes'], $attributes);
        $this->validate($request, $rule, $message, $attributes);

        $pricing = $this->scheduleValidation($request->get('sku'), $request->get('departure_date'),
            auth('api')->user()->company->id_company, $request->get('pax'));
        if ($pricing['status'] !== 200) {
            return response()->json([
                'message' => 'Something wrong with calculation ! please try again',
                'detail' => $pricing,
                'errors' => []
            ], 422);
        }

        try {
            \DB::beginTransaction();
            $product = Product::where('unique_code', $request->get('sku'))
                ->where('id_company', auth('api')->user()->company->id_company)
                ->where('status', 1)
                ->whereHas('schedule', function ($sch) use ($request) {
                    $sch->where('start_date', '<=', Carbon::parse($request->get('departure_date'))->toDateString())
                        ->where('end_date', '>=', Carbon::parse($request->get('departure_date'))->toDateString());
                })
                ->first();
            if (!$product) {
                return response()->json(['message' => '', 'Product not found/ not active'], 422);
            }

            $number = microtime(true);
            $number = str_replace('.', '', $number);
            $redeem = false;
            $discount = 0;
            $res = '';
            $voucher = null;
            $amount = (double)$pricing['result']['result']['grand_total'];
            if ($request->has('voucher_code') && $request->get('voucher_code') !== null && $request->get('voucher_code') !== '') {
                $voucher = Voucher::where('voucher_code', $request->get('voucher_code'))
                    ->where('id_company', auth('api')->user()->company->id_company)
                    ->where('status', 1)
                    ->first();

                // Limit minimal people
                if (!empty($voucher->min_people) && $request->input('pax') < $voucher->min_people) {
                    $res = 'min people';
                    $voucher = null;
                }

                // Limit maximal people
                if (!empty($voucher->max_people) && $request->input('pax') > $voucher->max_people) {
                    $res = 'min people';
                    $voucher = null;
                }

                // Limit products
                if ($voucher->products->isNotEmpty() && !in_array($product->id_product, $voucher->products->pluck('id_product')->toArray())) {
                    $res = 'Not valid for this product';
                    $voucher = null;
                }

                if ($voucher) {
                    if ($voucher->by_gomodo == '1') {
                        if (Carbon::now()->toDateString() >= $voucher->valid_start_date && Carbon::now()->toDateString() <= $voucher->valid_end_date) {
                            $totalUse = Order::where('id_voucher', $voucher->id_voucher)
                                ->whereIn('status', [0, 1, 2, 3, 4, 8])
                                ->where('created_at', '>=',
                                    Carbon::parse($voucher->valid_start_date)->toDateTimeString())
                                ->where('created_at', '<=', Carbon::parse($voucher->valid_end_date)->toDateTimeString())
                                ->count();
                            if ($voucher->max_use) {
                                if ($totalUse >= $voucher->max_use) {
                                    $res = 'max use';
                                    $voucher = null;
                                }
                            }
                        } else {
                            $res = 'Not Valid';
                            $voucher = null;
                        }
                    } else {
                        if ((!empty($voucher->valid_start_date) && today() < Carbon::parse($voucher->valid_start_date)) || (!empty($voucher->valid_end_date) && today() > Carbon::parse($voucher->valid_end_date))) {
                            $res = 'Not valid';
                            $voucher = null;
                        }

                        $totalUse = Order::where('id_voucher', $voucher->id_voucher)->whereIn('status',
                            [0, 1, 2, 3, 4, 8])->count();
                        if ($totalUse >= $voucher->max_use) {
                            $res = 'max use';
                            $voucher = null;
                        } elseif ($amount < $voucher->minimun_amount) {
                            $res = 'min not reached';
                            $voucher = null;
                        }
                    }
                }

            }
            if ($voucher) {
                if ($voucher->voucher_amount_type == '1') {
                    $res = 'amount_type 1';
                    $discount = (double)(($voucher->voucher_amount / 100) * $amount);
                } else {
                    $res = 'amount_type 0';
                    $discount = (double)$voucher->voucher_amount;
                }
                if ($voucher->up_to) {
                    if ($discount >= (double)$voucher->up_to) {
                        $discount = (double)$voucher->up_to;
                    }
                }
                if ($discount >= $amount) {
                    $res = 'ke redeem';
                    $discount = $amount;
                    $redeem = true;
                } else {
                    $res = 'enggak ke redeem';
                    $redeem = false;
                }
            }
            if ((double)$pricing['result']['result']['grand_total'] - (double)$discount <= 0) {
                $res = 'ke redeem';
                $redeem = true;
            }


            $invoice_no = 'INV' . date('ymd') . rand(100000, 999999);
            while ($check = Order::where('invoice_no', $invoice_no)->first()) {
                $invoice_no = 'INV' . date('ymd') . rand(100000, 999999);
            }

            $fee_payment = 0;
            $fee_credit_card = 0;
            $extra = 0;
            $extraInsurance = $this->setInsurance($request, $product);
//            dd($extraInsurance);
            if ($extraInsurance['success'] == true) {
                $extra = $extra + $extraInsurance['additionalOrder']['total'];
            }
            $listPayment = ListPayment::where('code_payment', $request->payment_method)->first();
            $companyPayment = CompanyPayment::where('company_id', auth('api')->user()->company->id_company)->where('payment_id', $listPayment->id)->first();
            if (empty($companyPayment)) {
                return [
                    'oke' => true,
                    'message' => 'Company Payment Not found'
                ];
            }
            $a = ((double)$pricing['result']['result']['grand_total'] + $extra - (double)$discount);
            if (optional($companyPayment)->charge_to == '1') {
                if ($listPayment->type == 'percentage') {
                    $fee = ceil(((100 / (100 - $listPayment->pricing_primary)) * $a) - $a);
                    //$fee_payment = ceil($fee + $listPayment->pricing_secondary);
                } else { //fixed
                    $fee_payment = ceil($listPayment->pricing_primary);
                }

                if ($listPayment->type_secondary == 'percentage') {
                    $fee_payment += ceil($fee + $listPayment->pricing_secondary);
                } else {
                    $fee_payment += $listPayment->pricing_secondary;
                }
            }
            if ($listPayment->code_payment == 'credit_card') {
                $fee_credit_card = $fee_payment;
                $fee_payment = 0;
            }
//             if (in_array($listPayment->code_payment, ['credit_card', 'gopay','indomaret', 'dana', 'linkaja','bca_va'])) {
// //                $fee_credit_card = ceil(((100 / 97.1) * $a) - $a);
//                 // fee to customer
//                 if (in_array($listPayment->code_payment, ['credit_card', 'dana', 'linkaja'])){
//                     if($listPayment->type == 'percentage'){
//                         $fee = ceil(((100 / (100 - $listPayment->pricing_primary)) * $a) - $a);
//                         $fee_credit_card = ceil($fee + $listPayment->pricing_secondary);
//                     } else { //fixed
//                         $fee_credit_card = ceil($listPayment->pricing_primary + $listPayment->pricing_secondary);
//                     }
//                     if (in_array($listPayment->code_payment, ['dana','linkaja'])){
//                         $fee_payment = $fee_credit_card;
//                         $fee_credit_card = 0;
//                     }
//                 } else {
//                     if ($listPayment->type == 'percentage') {
//                         $fee_payment = ceil((($listPayment->pricing_primary / 100) * $a) + $listPayment->pricing_secondary);
//                     } else { //fixed
//                         $fee_payment = ceil($listPayment->pricing_primary + $listPayment->pricing_secondary);
//                     }
//                 }
//             } else {
//                 $fee_credit_card = 0;
//                 $fee_payment = 0;
//             }

            $totalAmount = (((double)$pricing['result']['result']['grand_total'] - (double)$discount) + $extra + $fee_credit_card) + $fee_payment;

            // VAT
            $vat = 0;
            if ($product->vat) {
                $vat = $pricing['result']['result']['grand_total'] * 10 / 100;
                if (env('VAT_AFTER_PAYMENT_FEE', false)) {
                    $vat = $totalAmount * 10 / 100;
                }

                $totalAmount += $vat;
            }

            if (optional($companyPayment)->charge_to == '0' || optional($companyPayment)->charge_to == null) {
                $totalAmount = ceil(($totalAmount - $fee_payment) - $fee_credit_card);
            }
            if (in_array($listPayment->code_payment, ['virtual_account', 'credit_card', 'bca_manual', 'dana', 'ovo', 'linkaja', 'gopay', 'ovo_live', 'alfamart', 'indomaret'])) {
                if ($totalAmount < 10000) {
                    return apiResponse(400, \trans('booking.validation.min_ewallet'));
                }
            }
            if (in_array($request->input('payment_method'), ['alfamart', 'indomaret'])) {
                if ($totalAmount > 5000000) {
                    return apiResponse(400, \trans('booking.validation.alfamart'));
                }
            }

            if (in_array($listPayment->code_payment, ['dana', 'ovo', 'linkaja', 'gopay', 'ovo_live'])) {
                if ($totalAmount > 10000000) {
                    return apiResponse(400, \trans('booking.validation.payment'));
                }
            }

            $customer = null;
            if (!empty($request->input('email')) && !empty($request->input('full_name'))) {
                $customer = Customer::where('email', $request->input('email'))->where('id_company',
                    auth('api')->user()->company->id_company)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'id_company' => auth('api')->user()->company->id_company,
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
                        'id_company' => auth('api')->user()->company->id_company,
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
            }
            $dataOrder = [
                'invoice_no' => $invoice_no,
                'id_company' => auth('api')->user()->company->id_company,
                'transaction_type' => 0,
                'id_customer' => optional($customer)->id_customer ?? null,
                'id_user_agen' => null,
                'product_discount' => (double)$pricing['result']['result']['company_discount_price'],
                'amount' => (double)$pricing['result']['result']['total_price'],
                'fee_credit_card' => $fee_credit_card,
                'fee' => $fee_payment,
                'total_amount' => (int)ceil($totalAmount),
                'status' => $redeem ? 1 : 0,
                'is_void' => false,
                'external_notes' => $request->get('note'),
                'internal_notes' => null,
                'id_voucher' => $voucher ? $voucher->id_voucher : null,
                'voucher_type' => $voucher ? $voucher->voucher_type : null,
                'voucher_amount_type' => $voucher ? $voucher->voucher_amount_type : null,
                'voucher_code' => $voucher ? $voucher->voucher_code : null,
                'voucher_description' => $voucher ? $voucher->voucher_description : null,
                'voucher_amount' => $voucher ? $discount : 0,
                'allow_payment' => 1,
                'vat' => $vat,
                'payment_list' => $request->get('payment_list')
            ];
            $order = Order::create($dataOrder);
            $scanner = new \Milon\Barcode\DNS2D();
            \Storage::disk('public')->put('uploads/orders/'.$order->invoice_no.'.png',$scanner->getBarcodePNGPath($order->invoice_no, "QRCODE"));
            $oldPath = 'uploads/products/' . $product->main_image; // publc/images/1.jpg
            if (!\File::exists(public_path($oldPath))) {
                $oldPath = public_path('img/img2.jpg');
            }

            $fileExtension = \File::extension($oldPath);
            $newName = $invoice_no . '-' . $product->unique_code . '.' . $fileExtension;
            $newPathWithName = 'uploads/orders/' . $newName;

            \File::isDirectory(public_path('uploads/orders')) or \File::makeDirectory(public_path('uploads/orders'),
                0777, true, true);

            \File::copy($oldPath, $newPathWithName);

            $rate = 1;
            $dataDetail = [
                'invoice_no' => $invoice_no,
                'schedule_date' => $request->get('departure_date'),
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
                'product_total_price' => (double)$pricing['result']['result']['grand_total'] - (double)$discount,
                'adult' => $request->get('pax'),
                'children' => 0,
                'infant' => 0,
                'unit_name_id' => $product->pricing[0]->unit_name_id,
                'main_image' => $newName,
                'product_total_tax' => 0,
                'fee_name' => $product->fee_name,
                'fee_amount' => $product->fee_amount,
                'discount_name' => $pricing['result']['result']['company_discount_name'],
                'discount_amount_type' => $product->discount_amount_type,
                'discount_amount' => (double)$pricing['result']['result']['company_discount_price'],
                'notes' => $request->get('note'),
                'adult_price' => (double)$pricing['result']['result']['price'],
                'children_price' => 0,
                'infant_price' => 0,

            ];
            $detail = \App\Models\OrderDetail::create($dataDetail);
            $detailCustomer = [
                'invoice_no' => $invoice_no,
                'person_type' => 1,
                'first_name' => optional($customer)->first_name ?? 'Gomodo POS',
                'last_name' => null,
                'phone' => optional($customer)->phone,
                'email' => optional($customer)->email ?? null,
                'id_city' => $request->get('city'),
                'address' => $request->get('address'),
                'identity_number' => $request->get('identity_number'),
                'identity_number_type' => $request->get('identity_number_type'),
                'emergency_number' => $request->get('emergency_number'),
            ];
            \App\Models\OrderCustomer::create($detailCustomer);
            /*insurance*/

            if ($extraInsurance['success'] == true) {
                $additionalOrder = $order->additional_orders()->create($extraInsurance['additionalOrder']);
                $details = $additionalOrder->insurance_details()->createMany($extraInsurance['details']);
            }
            // Save to custom detail
            if (!empty($request->custom)) {
                if (!empty(array_filter($request->file('custom', [])))) {
                    $modify_request = $request->input('custom');
                    foreach ($request->file('custom') as $key => $value) {
                        foreach ($value as $k => $v) {
                            $modify_request[$key][$k] = $v->store('public/additional_info');
                        }
                    }

                    $request->merge([
                        'custom' => $modify_request
                    ]);
                }

                $schema = CustomSchema::select('type_custom', 'id', 'label_name')->where('product_id',
                    $product->id_product)->get();

                $custom_detail = collect($request->input('custom'))->map(function ($value, $participant) use (
                    $detail,
                    $schema
                ) {
                    return collect($value)->map(function ($value, $key) use ($detail, $participant, $schema) {
                        return [
                            'order_detail_id' => $detail->id,
                            'custom_schema_id' => $key,
                            'label_name' => $schema->where('id', $key)->first()->label_name,
                            'value' => $value,
                            'participant' => $participant,
                            'type_custom' => $schema->where('id', $key)->first()->type_custom
                        ];
                    })->values();
                });


                $detail->customDetail()->createMany($custom_detail->flatten(1)->toArray());
            }

            // END Save to custom detail

            if ($request->input('payment_list') == 'Midtrans Payment') {
                $this->midtransPay($redeem, $order, $invoice_no, $detail, $request, $product, $customer, $totalAmount);
                return response()->json([
                    'status' => 200,
                    'message' => 'New Order has been Created',
                    'data' => [
                        'invoice' => $invoice_no,
                        'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('memoria.retrieve.data', ['no_invoice' => $invoice_no]))
                    ]
                ]);
            }

            if ($request->input('payment_list') == 'Manual Transfer') {
                $payment = \App\Models\Payment::create([
                    'invoice_no' => $invoice_no,
                    'payment_gateway' => 'Manual Transfer BCA',
                    'reference_number' => 'manual-' . str_random('10'),
                    'status' => 'PENDING',
                    'amount' => $totalAmount,
                    'invoice_url' => null,
                    'expiry_date' => now()->addHours(24)->toDateTimeString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'response' => null,
                ]);

                $product_service = app('\App\Services\ProductService');
                if (!empty($request->input('email'))) {
                    $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
                }
                $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
                if (env('APP_ENV') !== 'local'):
                    $product_service->sendWACustomer($invoice_no);
                    $product_service->sendWAProvider($invoice_no);
                endif;
                \Log::info('WAProvider from' . HomeCtrl::class . ' line 724');
                \DB::commit();
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $order->company;
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New Online Booking Manual Transfer ' . Carbon::now()->format('d M Y H:i:s') . '**';
                $content .= '```';
                $content .= "Company Name    : " . $newCompany->company_name . "\n";
                $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
                $content .= "Email Company   : " . $newCompany->email_company . "\n";
                $content .= "Phone Number    : " . $newCompany->phone_company . "\n";
                $content .= "Invoice Name    : " . $order->order_detail->product_name . "\n";
                $content .= "Customer Name   : " . $order->customer_info->first_name . "\n";
                $content .= "Customer Email  : " . optional($order->customer_info)->email . "\n";
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

                $this->sendDiscordNotification($content, 'transaction');

                return response()->json([
                    'status' => 200,
                    'message' => 'New Order has been Created',
                    'data' => [
                        'invoice' => $invoice_no,
                        'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.bank-transfer', ['invoice' => $invoice_no]))
                    ]
                ]);
            }

            if ($request->input('payment_list') == 'COD') {
                $payment = \App\Models\Payment::create([
                    'invoice_no' => $invoice_no,
                    'payment_gateway' => 'Cash On Delivery',
                    'reference_number' => 'cod-' . str_random('10'),
                    'status' => 'PENDING',
                    'amount' => $totalAmount,
                    'invoice_url' => null,
                    'expiry_date' => null,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'response' => null,
                ]);


                $product_service = app('\App\Services\ProductService');
                // check jika ada email
                if (!empty($request->input('email'))) {
                    $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
                }
                $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
                if (env('APP_ENV') !== 'local'):
                    $product_service->sendWACustomer($invoice_no);
                    $product_service->sendWAProvider($invoice_no);
                endif;
                \Log::info('WAProvider from' . HomeCtrl::class . ' line 789');
//                $this->send_invoice(auth('api')->user()->company->id_company, $invoice_no);
//                $this->send_email_notif(auth('api')->user()->company->id_company, $invoice_no);
                \DB::commit();
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $order->company;
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New Online POS Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
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

                $this->sendDiscordNotification($content, 'transaction');

                return response()->json([
                    'status' => 200,
                    'message' => 'New Order has been Created',
                    'data' => [
                        'invoice' => $invoice_no,
                        'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.success', ['no_invoice' => $invoice_no]))
                    ]
                ]);
            }

            switch ($request->input('payment_method')) {
                case 'credit_card':
                    $paymentgateway = 'Xendit Credit Card';
                    break;
                case 'alfamart':
                    $paymentgateway = 'Xendit Alfamart';
                    break;
                case 'ovo':
                    $paymentgateway = 'Xendit Virtual Account OVO';
                    break;
                case 'kredivo':
                    $paymentgateway = 'Xendit Kredivo';
                    break;
                case 'ovo_live':
                    $paymentgateway = 'Xendit OVO';
                    break;
                case 'dana':
                    $paymentgateway = 'Xendit DANA';
                    break;
                case 'linkaja':
                    $paymentgateway = 'Xendit LinkAja';
                    break;
                default:
                    $paymentgateway = 'Xendit Virtual Account';
                    break;
            }

            if ($request->input('payment_list') == 'Xendit Payment') {
                if (!$redeem && $order->allow_payment == '1') {
                    if ($request->input('payment_method') == 'kredivo') {
                        $url = 'https://api.xendit.co/cardless-credit';
                        $price = $order->order_detail->product->pricing()
                            ->where('price_from', '<=', (int)$request->input('pax'))
                            ->where('price_until', '>=', 1)
                            ->first()
                            ->price;

                        $url_product = 'http' . ($request->secure() ? 's' : '') . '://' . $order->company->domain_memoria . '/product/detail' . $order->order_detail->product->unique_code;

                        $dataXendit = [
                            'cardless_credit_type' => 'KREDIVO',
                            'external_id' => $order->invoice_no,
                            'amount' => $order->total_amount,
                            'payment_type' => $request->input('kredivo_installment_duration'),
                            'items' => [
                                [
                                    'id' => $order->invoice_no,
                                    'name' => $order->order_detail->product_name,
                                    'price' => $price,
                                    'type' => 'Gomodo - ' . $order->order_detail->product->product_type->product_type_name,
                                    'url' => $url_product,
                                    'quantity' => $request->input('pax')
                                ]
                            ],
                            'customer_details' => [
                                'first_name' => $request->input('kredivo_first_name'),
                                'last_name' => $request->input('kredivo_last_name'),
                                'email' => $request->input('kredivo_email'),
                                'phone' => $request->input('kredivo_phone_number')
                            ],
                            'shipping_address' => [
                                'first_name' => $request->input('kredivo_first_name'),
                                'last_name' => $request->input('kredivo_last_name'),
                                'address' => $request->input('kredivo_address'),
                                'city' => \App\Models\City::where('id_city',
                                    $request->input('kredivo_city'))->first()->city_name,
                                'postal_code' => $request->input('kredivo_postal_code'),
                                'phone' => $request->input('kredivo_phone_number'),
                                'country_code' => 'IDN'
                            ],
                            'redirect_url' => route('invoice.kredivo', ['invoice' => $invoice_no]),
                            'callback_url' => route('kredivo.callback')
                            //'callback_url'          => 'https://29accc25.ngrok.io/kredivo/callback'
                        ];
                    } elseif ($request->input('payment_method') == 'ovo_live') {
//                        $url = 'https://api.xendit.co/ewallets';
//                        $dataXendit = [
//                            "external_id" => $order->invoice_no,
//                            "amount" => $order->total_amount,
//                            "phone" => $order->customer_info->phone,
//                            "ewallet_type" => "OVO"
//                        ];
                        // \DB::commit();
                        $this->storeOVO($order, $invoice_no, $paymentgateway, $request);

                        return response()->json([
                            'status' => 200,
                            'message' => 'New Order has been Created',
                            'data' => [
                                'invoice' => $invoice_no,
                                'url' => route('invoice.ovo', ['invoice' => $invoice_no])
                            ]
                        ]);
                    } elseif ($request->input('payment_method') == 'dana') {
                        $url = 'https://api.xendit.co/ewallets';
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "amount" => $order->total_amount,
                            // "callback_url" => 'https://3f3dcf6e.ngrok.io/dana/callback',
                            // "redirect_url" => 'http://3f3dcf6e.ngrok.io/',
                            "callback_url" => route('ewallet.callback'),
                            "redirect_url" => route('invoice.dana', ['invoice' => $invoice_no]),
                            "ewallet_type" => "DANA"
                        ];
                    } elseif ($request->input('payment_method') == 'linkaja') {
                        $url = 'https://api.xendit.co/ewallets';
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "phone" => $order->customer_info->phone,
                            "amount" => $order->total_amount,
                            "items" => [
                                [
                                    "id" => $order->invoice_no,
                                    "name" => $order->order_detail->product_name,
                                    "price" => $order->total_amount,
                                    "quantity" => $request->input('pax')
                                ]
                            ],
                            "callback_url" => route('ewallet.callback'),
                            "redirect_url" => route('invoice.linkaja', ['invoice' => $invoice_no]),
                            "ewallet_type" => "LINKAJA"
                        ];
                    } else {
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "amount" => $order->total_amount,
                            "payer_email" => $order->customer_info->email,
                            "description" => "INVOICE #" . $order->invoice_no . ' - ' . $order->order_detail->product_name
                        ];
                        if ($request->input('payment_method') == 'credit_card') {
                            $dataXendit['payment_methods '] = ["CREDIT_CARD"];
                        }
                        $url = $this->base_url . 'invoices';
                    }
                    $data_string = json_encode($dataXendit);
                    $res = json_decode($this->post_curl($url, $data_string));
                    if (isset($res->error_code)) {
                        \DB::rollBack();
                        return response()->json(['message' => $res->message], 403);
                    }

                    if ($request->input('payment_method') == 'kredivo') {
                        $paymentData = [
                            'invoice_no' => $invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'INCOMPLETE',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->redirect_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } elseif ($request->input('payment_method') == 'dana') {
                        $paymentData = [
                            'invoice_no' => $invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'PENDING',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->checkout_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } elseif ($request->input('payment_method') == 'linkaja') {
                        $paymentData = [
                            'invoice_no' => $invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'PENDING',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->checkout_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } else {
                        $paymentData = [
                            'invoice_no' => $invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => $res->id,
                            'status' => $res->status,
                            'amount' => $res->amount,
                            'invoice_url' => $res->invoice_url,
                            'expiry_date' => date('Y-m-d H:i:s', strtotime($res->expiry_date)),
                            'created_at' => date('Y-m-d H:i:s', strtotime($res->created)),
                            'updated_at' => date('Y-m-d H:i:s', strtotime($res->updated)),
                            'response' => $res,
                        ];
                    }

                    $payment = \App\Models\Payment::create($paymentData);

                    $product_service = app('\App\Services\ProductService');
                    $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
                    if (env('APP_ENV') !== 'local'):
                        $product_service->sendWAProvider($invoice_no);
                    endif;
                    \Log::info('WAProvider from' . HomeCtrl::class . ' line 1027');

                    if ($request->input('payment_method') != 'kredivo') {
                        $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
                        if (env('APP_ENV') !== 'local'):
                        $product_service->sendWACustomer($invoice_no);
                        endif;
                    } else {
                        $payment->kredivo()->create([
                            'first_name' => $request->input('kredivo_first_name'),
                            'last_name' => $request->input('kredivo_last_name'),
                            'phone_number' => $request->input('kredivo_phone_number'),
                            'email' => $request->input('kredivo_email'),
                            'address' => $request->input('kredivo_address'),
                            'city_id' => $request->input('kredivo_city'),
                            'postal_code' => $request->input('kredivo_postal_code'),
                            'installment_duration' => $request->input('kredivo_installment_duration')
                        ]);
                    }

                    //                $this->send_invoice(auth('api')->user()->company->id_company, $invoice_no);
                    //                $this->send_email_notif(auth('api')->user()->company->id_company, $invoice_no);
                    \DB::commit();
                    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                    $newCompany = $order->company;
                    $loc = \Stevebauman\Location\Facades\Location::get($ip);
                    $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                    $content = '**New Online Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
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


                    $this->sendDiscordNotification($content, 'transaction');

                    switch ($request->input('payment_method')) {
                        case 'credit_card':
                        case 'alfamart':
                        case 'ovo':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.' . str_replace('_', '-', $request->input('payment_method')),
                                ['invoice' => $invoice_no]));
                            break;
                        case 'kredivo':
                            $url = $res->redirect_url;
                            break;
                        case 'dana':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.dana', ['invoice' => $invoice_no]));
                            break;
                        case 'linkaja':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.linkaja', ['invoice' => $invoice_no]));
                            break;
                        case 'ovo_live':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.ovo', ['invoice' => $invoice_no]));
                            break;
                        default:
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.bank-transfer', ['invoice' => $invoice_no]));
                            break;
                    }

                    return response()->json([
                        'status' => 200,
                        'message' => 'New Order has been Created',
                        'data' => [
                            'invoice' => $invoice_no,
                            'url' => $url
                        ]
                    ]);
                } elseif ($redeem) {
                    $order->update(['status' => 1]);
                    $payment = \App\Models\Payment::create([
                        'invoice_no' => $invoice_no,
                        'status' => 'PAID',
                        'payment_gateway' => 'Redeem Voucher',
                        'created_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                        'updated_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                    ]);
                    $product_service = app('\App\Services\ProductService');
                    $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
                    $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
                    if (env('APP_ENV') !== 'local'):
                    $product_service->sendWACustomer($invoice_no);
                    $product_service->sendWAProvider($invoice_no);
                    endif;
                    \Log::info('WAProvider from' . HomeCtrl::class . ' line 1128');
//                $this->send_invoice(auth('api')->user()->company->id_company, $invoice_no);
//                $this->send_email_notif(auth('api')->user()->company->id_company, $invoice_no);
                    \DB::commit();
                    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                    $newCompany = $order->company;
                    $loc = \Stevebauman\Location\Facades\Location::get($ip);
                    $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                    $content = '**New Redeem Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
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

                    $this->sendDiscordNotification($content, 'transaction');
                    return response()->json([
                        'status' => 200,
                        'message' => 'New Order has been Created',
                        'data' => [
                            'invoice' => $invoice_no,
                            'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('memoria.retrieve.data', ['no_invoice' => $invoice_no]))
                        ]
                    ]);
                }
            }
        } catch (\Exception $exception) {
            \DB::rollBack();

            return response()->json(
                [
                    'code' => $exception->getCode(),
                    'trace' => $exception->getTrace(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage()
                ]

                , 500);
        }
        return response()->json($pricing);

    }

    public function payOffline($request)
    {
        $rules = [
            'payment_method' => 'required|in:credit_card,virtual_account,alfamart,ovo,indomaret,gopay,alfamart_midtrans,ovo_live,dana,linkaja,bca_va,kredivo,bca_manual,akulaku',
            'invoice_no' => Rule::exists('tbl_order_header', 'invoice_no')
                ->where('id_company', auth('api')->user()->company->id_company)
        ];

        $attributes = [];
        if ($request->input('payment_method') == 'kredivo') {
            $rules = array_merge($rules, [
                'kredivo_first_name' => 'required',
                'kredivo_last_name' => 'required',
                'kredivo_phone_number' => 'required|numeric|digits_between:6,20',
                'kredivo_email' => 'required|email',
                'kredivo_address' => 'required',
                'kredivo_state' => 'required|exists:tbl_state,id_state',
                'kredivo_city' => 'required|exists:tbl_city,id_city',
                'kredivo_postal_code' => 'required|numeric|min:5',
                'kredivo_installment_duration' => [
                    'required',
                    Rule::in(array_keys(\App\Models\PaymentKredivo::$durations))
                ]
            ]);

            $attributes = collect(trans('customer.kredivo.form'))
                ->mapWithKeys(function ($value, $key) {
                    return ['kredivo_' . $key => strtolower($value)];
                })
                ->toArray();
        }

        $this->validate($request, $rules, [], $attributes);
        $order = Order::find($request->invoice_no);
        if ($order && $order->payment) {
            try {
                \DB::beginTransaction();
                if ($order->payment->reference_number) {
                    if (in_array($order->payment->payment_gateway, ['Xendit Virtual Account', 'Xendit Virtual Account OVO', 'Manual Transfer BCA'])) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.bank-transfer', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit Credit Card') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.credit-card', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit Alfamart') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.alfamart', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit OVO') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.ovo', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit DANA') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.dana', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit LinkAja') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.linkaja', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                    if (in_array($order->payment->payment_gateway, ['Midtrans Indomaret', 'Midtrans Gopay', 'Midtrans Virtual Account BCA', 'Midtrans AkuLaku'])) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('check-invoice.invoice.midtrans', ['invoice' => $request->invoice_no]))
                            ]
                        ]);
                    }
                }

                $amount = $order->total_amount;
                $voucher = null;
                $res = '';
                $redeem = false;

                if ($request->has('voucher_code') && $request->get('voucher_code') !== null && $request->get('voucher_code') !== '') {
                    $voucher = Voucher::where('voucher_code', $request->get('voucher_code'))->where('id_company',
                        auth('api')->user()->company->id_company)->first();
                    $totalUse = Order::where('id_voucher', $voucher->id_voucher)->whereIn('status',
                        [0, 1, 2, 3, 4, 8])->count();
                    if ($totalUse >= $voucher->max_use) {
                        $res = 'max use';
                        $voucher = null;
                    } elseif ($amount < $voucher->minimun_amount) {
                        $res = 'min not reached';
                        $voucher = null;
                    }
                }
                $discount = 0;
                if ($voucher) {
                    if ($voucher->voucher_amount_type == '1') {
                        $res = 'amount_type 1';
                        $discount = (double)(($voucher->voucher_amount / 100) * $amount);
                    } else {
                        $res = 'amount_type 0';
                        $discount = (double)$voucher->voucher_amount;
                    }
                    if ($voucher->up_to) {
                        if ($discount >= (double)$voucher->up_to) {
                            $discount = (double)$voucher->up_to;
                        }
                    }
                    if ($discount >= $amount) {
                        $res = 'ke redeem';
                        $discount = $amount;
                        $redeem = true;
                    } else {
                        $res = 'enggak ke redeem';
                        $redeem = false;
                    }
                    $order->update([
                        'id_voucher' => $voucher ? $voucher->id_voucher : null,
                        'voucher_type' => $voucher ? $voucher->voucher_type : null,
                        'voucher_amount_type' => $voucher ? $voucher->voucher_amount_type : null,
                        'voucher_code' => $voucher ? $voucher->voucher_code : null,
                        'voucher_description' => $voucher ? $voucher->voucher_description : null,
                        'voucher_amount' => $voucher ? $discount : 0,
                    ]);
                }
//                $amount = $amount - $discount;
//                $order->update([
//                    'total_amount' => $amount
//                ]);

                $fee_payment = 0;
                $fee_credit_card = 0;
                $listPayment = ListPayment::where('code_payment', $request->payment_method)->first();
                $companyPayment = CompanyPayment::where('company_id', auth('api')->user()->company->id_company)->where('payment_id', $listPayment->id)->first();

                if (empty($companyPayment)) {
                    return [
                        'oke' => true,
                        'message' => 'Company Payment Not found'
                    ];
                }

                if (optional($companyPayment)->charge_to == '1') {
                    if ($listPayment->type == 'percentage') {
                        $fee = ceil(((100 / (100 - $listPayment->pricing_primary)) * $amount) - $amount);
                        //$fee_payment = ceil($fee + $listPayment->pricing_secondary);
                    } else { //fixed
                        $fee_payment = ceil($listPayment->pricing_primary);
                    }

                    if ($listPayment->type_secondary == 'percentage') {
                        $fee_payment += ceil($fee + $listPayment->pricing_secondary);
                    } else {
                        $fee_payment += $listPayment->pricing_secondary;
                    }
                }
                if ($listPayment->code_payment == 'credit_card') {
                    $fee_credit_card = $fee_payment;
                    $fee_payment = 0;
                }

                $totalAmount = (((double)$order->total_amount - (double)$discount) + $fee_credit_card) + $fee_payment;
                if (optional($companyPayment)->charge_to == '0' || optional($companyPayment)->charge_to == null) {
                    $totalAmount = ceil(($totalAmount - $fee_credit_card) - $fee_payment);
                }

                if (in_array($request->input('payment_method'), ['alfamart', 'indomaret'])) {
                    if ($totalAmount > 5000000) {
                        return apiResponse(400, \trans('booking.validation.alfamart'));
                    }
                    if ($totalAmount < 10000) {
                        return apiResponse(400, \trans('booking.validation.alfamart_min'));
                    }
                }
                if (in_array($listPayment->code_payment, ['dana', 'ovo', 'linkaja'])) {
                    if ($totalAmount > 10000000) {
                        return apiResponse(400, \trans('booking.validation.payment'));
                    }

                    if ($totalAmount < 10000) {
                        return apiResponse(400, \trans('booking.validation.min_ewallet'));
                    }
                }
                $order->update([
                    'fee_credit_card' => $fee_credit_card,
                    'fee' => $fee_payment,
                    'total_amount' => $totalAmount
                ]);

                if ($request->input('payment_list') == 'Midtrans Payment') {
                    $order->update([
                        'payment_list' => 'Midtrans Payment',
                    ]);
                    \DB::commit();
                    $this->midtransPay($redeem, $order, $order->invoice_no, $order->order_detail, $request, $order->order_detail->product, $order->customer, $totalAmount);
                    return response()->json([
                        'status' => 200,
                        'message' => 'New Order has been Created',
                        'data' => [
                            'invoice' => $order->invoice_no,
                            'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no]))
                        ]
                    ]);
                }

                if ($request->input('payment_list') == 'Manual Transfer') {
                    $order->update([
                        'payment_list' => 'Manual Transfer',
                    ]);

                    $order->payment->update([
                        'invoice_no' => $order->invoice_no,
                        'payment_gateway' => 'Manual Transfer BCA',
                        'reference_number' => 'manual-' . str_random('10'),
                        'expiry_date' => now()->addHours(24)->toDateTimeString(),
                        'updated_at' => now(),
                    ]);

                    \DB::commit();
                    return response()->json([
                        'status' => 200,
                        'message' => 'New Order has been Created',
                        'data' => [
                            'invoice' => $order->invoice_no,
                            'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.bank-transfer', ['invoice' => $order->invoice_no]))
                        ]
                    ]);
                }

                if ($request->input('payment_list') == 'Xendit Payment') {
                    if ($redeem) {
                        $order->update(['status' => 1]);
                        $payment = $order->payment->update([
                            'invoice_no' => $order->invoice_no,
                            'status' => 'PAID',
                            'payment_gateway' => 'Redeem Voucher',
                            'reference_number' => 'Redem' . generateRandomString(12),
                            'updated_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                        ]);
                        $product_service = app('\App\Services\ProductService');
                        $product_service->allMailProvider(auth('api')->user()->company->id_company, $order->invoice_no);
                        $product_service->allMailCustomer(auth('api')->user()->company->id_company, $order->invoice_no);
                        if (env('APP_ENV') !== 'local'):
                        $product_service->sendWACustomer($order->invoice_no);
                        $product_service->sendWAProvider($order->invoice_no);
                        endif;
                        \Log::info('WAProvider from' . HomeCtrl::class . ' line 2274');

                        //                    $this->send_invoice(auth('api')->user()->company->id_company, $order->invoice_no);
                        //                    $this->send_email_notif(auth('api')->user()->company->id_company, $order->invoice_no);
                        \DB::commit();
                        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                        $newCompany = $order->company;
                        $loc = \Stevebauman\Location\Facades\Location::get($ip);
                        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                        $content = '**New E-Invoice' . Carbon::now()->format('d M Y H:i:s') . '**';
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


                        $this->sendDiscordNotification($content, 'transaction');
                        return response()->json([
                            'status' => 200,
                            'message' => 'New Order has been Created',
                            'data' => [
                                'invoice' => $order->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no]))
                            ]
                        ]);
                    }

                    switch ($request->input('payment_method')) {
                        case 'credit_card':
                            $paymentgateway = 'Xendit Credit Card';
                            break;
                        case 'alfamart':
                            $paymentgateway = 'Xendit Alfamart';
                            break;
                        case 'ovo':
                            $paymentgateway = 'Xendit Virtual Account OVO';
                            break;
                        case 'kredivo':
                            $paymentgateway = 'Xendit Kredivo';
                            break;
                        case 'ovo_live':
                            $paymentgateway = 'Xendit OVO';
                            break;
                        case 'dana':
                            $paymentgateway = 'Xendit DANA';
                            break;
                        case 'linkaja':
                            $paymentgateway = 'Xendit LinkAja';
                            break;
                        default:
                            $paymentgateway = 'Xendit Virtual Account';
                            break;
                    }

                    if ($request->input('payment_method') == 'kredivo') {
                        $order->payment->kredivo()->create([
                            'first_name' => $request->input('kredivo_first_name'),
                            'last_name' => $request->input('kredivo_last_name'),
                            'phone_number' => $request->input('kredivo_phone_number'),
                            'email' => $request->input('kredivo_email'),
                            'address' => $request->input('kredivo_address'),
                            'city_id' => $request->input('kredivo_city'),
                            'postal_code' => $request->input('kredivo_postal_code'),
                            'installment_duration' => $request->input('kredivo_installment_duration')
                        ]);
                        $url = 'https://api.xendit.co/cardless-credit';
                        $price = $order->amount;
                        $items = collect($order->invoice_detail)->map(function ($data, $key) use ($order) {
                            return [
                                'id' => $order->invoice_no . '-' . ($key + 1),
                                'name' => $data['description'],
                                'price' => (int)$data['price'],
                                'type' => 'Gomodo - ' . $order->order_detail->product->product_type->product_type_name,
                                'quantity' => (int)$data['qty']
                            ];
                        })
                            ->values()
                            ->toArray();

                        $url_product = 'http' . ($request->secure() ? 's' : '') . '://' . $order->company->domain_memoria . '/product/detail' . $order->order_detail->product->unique_code;

                        $dataXendit = [
                            'cardless_credit_type' => 'KREDIVO',
                            'external_id' => $order->invoice_no,
                            'amount' => $order->total_amount,
                            'payment_type' => $request->input('kredivo_installment_duration'),
                            'items' => [
                                [
                                    'id' => $order->invoice_no,
                                    'name' => $order->order_detail->product_name,
                                    'price' => $price,
                                    'type' => 'Gomodo - ' . $order->order_detail->product->product_type->product_type_name,
                                    'url' => $url_product,
                                    'quantity' => 1
                                ]
                            ],
                            'customer_details' => [
                                'first_name' => $request->input('kredivo_first_name'),
                                'last_name' => $request->input('kredivo_last_name'),
                                'email' => $request->input('kredivo_email'),
                                'phone' => $request->input('kredivo_phone_number')
                            ],
                            'shipping_address' => [
                                'first_name' => $request->input('kredivo_first_name'),
                                'last_name' => $request->input('kredivo_last_name'),
                                'address' => $request->input('kredivo_address'),
                                'city' => \App\Models\City::where('id_city',
                                    $request->input('kredivo_city'))->first()->city_name,
                                'postal_code' => $request->input('kredivo_postal_code'),
                                'phone' => $request->input('kredivo_phone_number'),
                                'country_code' => 'IDN'
                            ],
                            'redirect_url' => route('invoice.kredivo', ['invoice' => $order->invoice_no]),
                            'callback_url' => route('kredivo.callback')
                            //'callback_url'          => 'https://29accc25.ngrok.io/kredivo/callback'
                        ];
                    } elseif ($request->input('payment_method') == 'dana') {
                        $url = 'https://api.xendit.co/ewallets';
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "amount" => $order->total_amount,
                            "callback_url" => route('ewallet.callback'),
                            "redirect_url" => route('invoice.dana', ['invoice' => $order->invoice_no]),
                            "ewallet_type" => "DANA"
                        ];
                    } elseif ($request->input('payment_method') == 'linkaja') {
                        $url = 'https://api.xendit.co/ewallets';
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "phone" => $order->customer_info->phone,
                            "amount" => $order->total_amount,
                            "items" => [
                                [
                                    "id" => $order->invoice_no,
                                    "name" => $order->order_detail->product_name,
                                    "price" => $order->total_amount,
                                    "quantity" => $request->input('pax')
                                ]
                            ],
                            "callback_url" => route('ewallet.callback'),
                            "redirect_url" => route('invoice.linkaja', ['invoice' => $order->invoice_no]),
                            "ewallet_type" => "LINKAJA"
                        ];
                    } elseif ($request->input('payment_method') == 'ovo_live') {
                        // \DB::commit();
                        $this->storeOVO($order, $order->invoice_no, $paymentgateway, $request);

                        return response()->json([
                            'status' => 200,
                            'message' => 'New Order has been Created',
                            'data' => [
                                'invoice' => $order->invoice_no,
                                'url' => str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.ovo', ['invoice' => $order->invoice_no]))
                            ]
                        ]);
                    } else {
                        $dataXendit = [
                            "external_id" => $order->invoice_no,
                            "amount" => $order->total_amount,
                            "payer_email" => $order->customer_info->email,
                            "description" => "INVOICE #" . $order->invoice_no . ' - ' . $order->order_detail->product_name,
                            "invoice_duration" => Carbon::now()->diffInSeconds(Carbon::parse($order->payment->expiry_date))
                        ];
                        if ($request->input('payment_method') == 'credit_card') {
                            $dataXendit['payment_methods '] = ["CREDIT_CARD"];
                        }
                        $url = $this->base_url . 'invoices';
                    }


                    $data_string = json_encode($dataXendit);
                    $res = json_decode($this->post_curl($url, $data_string));
                    if (isset($res->error_code)) {
                        \DB::rollBack();
                        return response()->json([
                            'message' => $res,
                            'request' => $dataXendit,
                            'fee' => $fee_credit_card
                        ], 403);
                    }

                    if ($request->input('payment_method') == 'kredivo') {
                        $paymentData = [
                            'invoice_no' => $request->invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'INCOMPLETE',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->redirect_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } elseif ($request->input('payment_method') == 'dana') {
                        $paymentData = [
                            'invoice_no' => $request->invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'PENDING',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->checkout_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } elseif ($request->input('payment_method') == 'linkaja') {
                        $paymentData = [
                            'invoice_no' => $request->invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'PENDING',
                            'amount' => $order->total_amount,
                            'invoice_url' => $res->checkout_url,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } elseif ($request->input('payment_method') == 'ovo_live') {
                        $paymentData = [
                            'invoice_no' => $request->invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => null,
                            'status' => 'PENDING',
                            'amount' => $order->total_amount,
                            'invoice_url' => null,
                            'expiry_date' => now()->addHours(24)->toDateTimeString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'response' => $res,
                        ];
                    } else {
                        $paymentData = [
                            'invoice_no' => $request->invoice_no,
                            'payment_gateway' => $paymentgateway,
                            'reference_number' => $res->id,
                            'status' => $res->status,
                            'amount' => $res->amount,
                            'invoice_url' => $res->invoice_url,
                            'expiry_date' => date('Y-m-d H:i:s', strtotime($res->expiry_date)),
                            'created_at' => date('Y-m-d H:i:s', strtotime($res->created)),
                            'updated_at' => date('Y-m-d H:i:s', strtotime($res->updated)),
                            'response' => $res,
                        ];
                    }

                    $payment = $order->payment->update($paymentData);

                    \DB::commit();
                    // anumu
                    switch ($request->input('payment_method')) {
                        case 'credit_card':
                        case 'alfamart':
                        case 'ovo':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.' . str_replace('_', '-', $request->input('payment_method')),
                                ['invoice' => $request->invoice_no]));
                            break;
                        case 'kredivo':
                            $url = $res->redirect_url;
                            break;
                        case 'dana':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.dana', ['invoice' => $request->invoice_no]));
                            break;
                        case 'linkaja':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.linkaja', ['invoice' => $request->invoice_no]));
                            break;
                        case 'ovo_live':
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.ovo', ['invoice' => $request->invoice_no]));
                            break;
                        default:
                            $url = str_replace(env('APP_URL'),auth('api')->user()->company->domain_memoria,route('invoice.bank-transfer', ['invoice' => $request->invoice_no]));
                            break;
                    }

                    return response()->json([
                        'status' => 200,
                        'message' => 'Redirecting to payment page',
                        'data' => [
                            'invoice' => $request->invoice_no,
                            'url' => $url
                        ]
                    ]);
                }

            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }

        return response()->json([
            'status' => 400,
            'message' => 'Something Wrong with the order',
            'data' => []
        ]);


    }

    public function storeOVO($order, $invoice_no, $paymentgateway, $request)
    {
        $paymentData = [
            'invoice_no' => $invoice_no,
            'payment_gateway' => $paymentgateway,
            'reference_number' => generateRandomString(12),
            'status' => 'PENDING',
            'amount' => $order->total_amount,
            'invoice_url' => null,
            'expiry_date' => now()->addHours(24)->toDateTimeString(),
            'created_at' => now(),
            'updated_at' => now(),
            'response' => null,
        ];
        if (isset($order->payment)) {
            $payment = $order->payment->update($paymentData);
        } else {
            $payment = \App\Models\Payment::create($paymentData);

            $product_service = app('\App\Services\ProductService');
            $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
            $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
            if (env('APP_ENV') !== 'local'):
            $product_service->sendWACustomer($invoice_no);
            $product_service->sendWAProvider($invoice_no);
            endif;
            \Log::info('WAProvider from' . HomeCtrl::class . ' line 1215');

            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = $order->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Online Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name    : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Email Company   : " . $newCompany->email_company . "\n";
            $content .= "Phone Number    : " . $newCompany->phone_company . "\n";
            $content .= "Invoice Name    : " . $order->order_detail->product_name . "\n";
            $content .= "Customer Name   : " . $order->customer_info->first_name . "\n";
            $content .= "Customer Email  : " . $order->customer_info->email . "\n";
            $content .= "Total Nominal   : " . format_priceID($order->total_amount) . "\n";
            $content .= "Payment Method  : " . $paymentgateway . "\n";
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
            $this->sendDiscordNotification($content, 'transaction');
        }
        \DB::commit();
    }

    private function post_curl($url, $data_string)
    {
        //dd($url, $data_string);
        $headers = array(
            'Content-Type:application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->xendit_key . ":");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        return $result;
    }

    private function setInsurance(Request $request, $product)
    {
        $result = [
            'additionalOrder' => [],
            'details' => [],
            'success' => false
        ];
        if ($request->has('use_insurance') && count($request->get('use_insurance')) == '1') {
            $insurance = \App\Models\Insurance::where('id',
                array_keys($request->get('use_insurance'))[0])->active()->first();
            if ($insurance && $product->insurances()->where('id', $insurance->id)->first()) {
                if ($product->duration_type == '0') {
                    $duration = ceil($product->duration / 24);
                } else {
                    $duration = $product->duration;
                }

                $totalInsurancePrice = 0;
                if ($insurance->active_pricings->count() > 0):
                    $insurancePrice = $insurance->active_pricings()->where('day', '>=', $duration)->first()->price;
                    if (!$insurancePrice) {
                        $insurancePrice = $insurancePrice->active_pricings()->orderBy('day', 'desc')->first()->price;
                    }
                    $totalInsurancePrice = (int)$request->get('pax') * $insurancePrice;
                endif;
                $additionalOrder = [
                    'quantity' => $request->get('pax'),
                    'price' => $insurancePrice,
                    'total' => $totalInsurancePrice,
                    'description_id' => 'Pembayaran Asuransi ' . $insurance->insurance_name_id,
                    'description_en' => $insurance->insurance_name_en . ' insurance',
                    'type' => 'insurance'
                ];

                $insuranceDetails = [];
                foreach ($request->get('insurances') as $insuranceForm):
                    if (isset($insuranceForm['customer'])):
                        foreach ($insuranceForm['customer'] as $item => $value):
                            if ($modelInsuranceForm = \App\Models\InsuranceForm::where('insurance_id',
                                $insurance->id)->where('name', $item)->first()):
                                $insuranceDetails[] = [
                                    'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                                    'purpose_order' => 1,
                                    'insurance_form_id' => $modelInsuranceForm->id,
                                    'label_id' => $modelInsuranceForm->label_id,
                                    'label_en' => $modelInsuranceForm->label_en,
                                    'value' => $value,
                                    'purpose' => 'customer',
                                    'type' => $modelInsuranceForm->type
                                ];
                            endif;
                        endforeach;
                    endif;
                    if (isset($insuranceForm['participants'])):
                        foreach ($insuranceForm['participants'] as $item => $field):
                            foreach ($field as $item2 => $value):
                                if ($modelInsuranceForm = \App\Models\InsuranceForm::where('insurance_id',
                                    $insurance->id)->where('name', $item2)->first()):
                                    $insuranceDetails[] = [
                                        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                                        'purpose_order' => $item,
                                        'insurance_form_id' => $modelInsuranceForm->id,
                                        'label_id' => $modelInsuranceForm->label_id,
                                        'label_en' => $modelInsuranceForm->label_en,
                                        'value' => $value,
                                        'purpose' => 'participants',
                                        'type' => $modelInsuranceForm->type
                                    ];
                                endif;
                            endforeach;
                        endforeach;
                    endif;
                endforeach;
                $result['success'] = true;
                $result['additionalOrder'] = $additionalOrder;
                $result['details'] = $insuranceDetails;
            }
        }
        return $result;
    }

    public function checkVoucher(Request $request)
    {
        $rule = [
            'id_product' => [
                'required',
                Rule::exists('tbl_product', 'id_product')->where('id_company',
                    auth('api')->user()->company->id_company)->where('status', 1)
            ],
            'voucher_code' => [
                'required',
                Rule::exists('tbl_voucher', 'voucher_code')->where('id_company',
                    auth('api')->user()->company->id_company)->where('status', '1'),
            ],
            'amount' => 'required|numeric|min:0'
        ];
        $this->validate($request, $rule);
        $product = Product::withoutGlobalScopes()->where('id_product', $request->id_product)->first();
        $voucher = Voucher::where('voucher_code', $request->get('voucher_code'))->where('id_company',
            auth('api')->user()->company->id_company)->first();

        // Limit minimal people
        if (!empty($voucher->min_people) && $request->input('pax') < $voucher->min_people) {
            if ($product->booking_type == 'online'):
                return response()->json([
                    'message' => '', 'errors' => ['voucher_code' => ['Min ' . $voucher->min_people . ' people']]
                ], 422);
            endif;
        }

        // Limit products
        if ($voucher->products->isNotEmpty() && !in_array($request->input('id_product'), $voucher->products->pluck('id_product')->toArray())) {
            return response()->json(['message' => '', 'errors' => ['voucher_code' => ['Voucher is not valid for this product']]],
                422);
        }

        // Limit maximal people
        if (!empty($voucher->max_people) && $request->input('pax') > $voucher->max_people) {
            return response()->json([
                'message' => '', 'errors' => ['voucher_code' => ['Max ' . $voucher->max_people . ' people']]
            ], 422);
        }

        if ($voucher->by_gomodo == '1') {

            if (!$product || $product->booking_confirmation == '0') {
                return response()->json(['message' => '', 'errors' => ['voucher_code' => ['Voucher is not valid']]],
                    422);
            }

            if (Carbon::now()->toDateString() >= $voucher->valid_start_date && Carbon::now()->toDateString() <= $voucher->valid_end_date) {
                $totalUse = Order::where('id_voucher', $voucher->id_voucher)
                    ->whereIn('status', [0, 1, 2, 3, 4, 8])
                    ->where('created_at', '>=', Carbon::parse($voucher->valid_start_date)->toDateTimeString())
                    ->where('created_at', '<=', Carbon::parse($voucher->valid_end_date)->toDateTimeString())
                    ->count();
                if ($voucher->max_use) {
                    if ($totalUse >= $voucher->max_use) {
                        return response()->json([
                            'message' => '',
                            'errors' => ['voucher_code' => ['Max Voucher has been reached']]
                        ], 422);
                    }
                }
                if ((double)$request->get('amount') < $voucher->minimum_amount) {
                    return response()->json([
                        'message' => '',
                        'errors' => ['voucher_code' => ['Minimum payment is ' . format_priceID($voucher->minimum_amount)]]
                    ], 422);
                }
                if ($voucher->voucher_amount_type == 1) {
                    $discount = (double)(($voucher->voucher_amount / 100) * (double)$request->get('amount'));
                } else {
                    $discount = (double)$voucher->voucher_amount;
                }
                if ($voucher->up_to) {
                    if ($discount >= (double)$voucher->up_to) {
                        $discount = (double)$voucher->up_to;
                    }
                }
                if ($discount >= (double)$request->get('amount')) {
                    $discount = (double)$request->get('amount');
                    $redeem = true;
                } else {
                    $redeem = false;
                }
            } else {
                return response()->json(['message' => '', 'errors' => ['voucher_code' => ['Voucher is not valid']]],
                    422);
            }

        } else {
            if ((!empty($voucher->valid_start_date) && today() < Carbon::parse($voucher->valid_start_date)) || (!empty($voucher->valid_end_date) && today() > Carbon::parse($voucher->valid_end_date))) {
                return response()->json(['message' => '', 'errors' => ['voucher_code' => ['Voucher is not valid']]],
                    422);
            }

            $totalUse = Order::where('id_voucher', $voucher->id_voucher)->whereIn('status',
                [0, 1, 2, 3, 4, 8])->count();
            if ($voucher->max_use) {
                if ($totalUse >= $voucher->max_use) {
                    return response()->json([
                        'message' => '',
                        'errors' => ['voucher_code' => ['Max Voucher has been reached']]
                    ], 422);
                }
            }
            if ((double)$request->get('amount') < $voucher->minimum_amount) {
                return response()->json([
                    'message' => '',
                    'errors' => ['voucher_code' => ['Minimum payment is ' . format_priceID($voucher->minimum_amount)]]
                ], 422);
            }
            if ($voucher->voucher_amount_type == 1) {
                $discount = (double)(($voucher->voucher_amount / 100) * (double)$request->get('amount'));
            } else {
                $discount = (double)$voucher->voucher_amount;
            }
            if ($voucher->up_to) {
                if ($discount >= (double)$voucher->up_to) {
                    $discount = (double)$voucher->up_to;
                }
            }
            if ($discount >= (double)$request->get('amount')) {
                $discount = (double)$request->get('amount');
                $redeem = true;
            } else {
                $redeem = false;
            }

        }


        return response()->json([
            'message' => 'OK',
            'discount' => $discount,
            'discount_text' => $voucher->currency . ' -' . number_format($discount, 0, '', '.'),
            'redeem' => $redeem
        ]);

    }

    public function manualApprove($invoice)
    {
        if (!$order = Order::find($invoice)) {
            return apiResponse(404, 'Order not found');
        }
        if ($order->status != '0') {
            return apiResponse(403, 'Status has changed');
        }
        if ($order->payment->payment_gateway === 'Cash On Delivery') {
            $order->payment()->update(['status' => 'PAID']);
            $order->update(['status' => 1]);
            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer($order->id_company, $order->invoice_no);
            return apiResponse(200, 'Payment Approved');
        }
        return apiResponse(400, 'Only accept manual cash', $order->payment);
    }

    public function approveCustomer($invoice)
    {
        if (!$order = Order::find($invoice)) {
            return apiResponse(404, 'Order not found');
        }

        if ($order->status != '1') {
            return apiResponse(403, 'Wrong status');
        }
        if ($order->is_checked) {
            return apiResponse(403, 'Already Checked');
        }
        if ($order->order_detail->schedule_date !== now()->toDateString()) {
            return apiResponse(403, 'Wrong event date');
        }

        $order->update(['is_checked' => 1]);
        return apiResponse(200, 'User Approved');
    }

    public function orderList(Request $request)
    {
        $orders = Order::with('order_detail.customDetail', 'payment')
            ->when($request->filled('invoice'), function ($o) use ($request) {
                $o->where('invoice_no', 'like', '%' . $request->get('invoice') . '%');
            })->where('id_company', auth('api')->user()->company->id_company)
            ->orderBy('created_at','desc')
            ->paginate(12);

        return apiResponse(200, 'ok', $orders);
    }

    public function orderDetail($id)
    {
        $order = Order::with('order_detail.customDetail', 'payment')
            ->where('invoice_no', $id)->where('id_company', auth('api')->user()->company->id_company)->first();
        if (!$order) {
            return apiResponse(404, 'not found');
        }
        return apiResponse(200, 'ok', $order);
    }
    public function payMidtrans(Request $request)
    {
        $product = Product::find($request->id_product);


        $array = [
            'transaction_details' => [
                'order_id' => str_random(14),
                'gross_amount' => 10000
            ],
            'item_details' => [
                'id' => 'ITEM1',
                'price' => 10000,
                'quantity' => 1,
                'name' => 'Midtrans Bear',
                'brand' => 'Midtrans',
                'category' => 'Toys',
                'merchant_name' => 'Midtrans'
            ],
            'enabled_payments' => [
                'credit_card',
                'mandiri_clickpay',
                'cimb_clicks',
                'bca_klikbca',
                'bca_klikpay',
                'bri_epay',
                'telkomsel_cash',
                'echannel',
                'indosat_dompetku',
                'mandiri_ecash',
                'permata_va',
                'bca_va',
                'bni_va',
                'other_va',
                'kioson',
                'indomaret',
                'gci',
                'danamon_online'
            ],
            'credit_card' => [
                'secure' => true,
                'channel' => 'migs',
                'bank' => 'bca',
                'installment' => [
                    'required' => false,
                    'terms' => [
                        'bni' => [3, 6, 12],
                        'mandiri' => [3, 6, 12],
                        'cimb' => [3],
                        'bca' => [3, 6, 12],
                        'offline' => [6, 12]
                    ]
                ],
                'whitelist_bins' => [
                    '48111111',
                    '41111111'
                ]
            ],
            'customer_details' => [
                'first_name' => 'TEST',
                'last_name' => 'MIDTRANSER',
                'email' => 'test@midtrans.com',
                'phone' => '+628123456',
                'billing_address' => [
                    'first_name' => 'TEST',
                    'last_name' => 'MIDTRANSER',
                    'email' => 'test@midtrans.com',
                    'phone' => '081 2233 44-55',
                    'address' => 'Sudirman',
                    'city' => 'Jakarta',
                    'postal_code' => '12190',
                    'country_code' => 'IDN'
                ],
                'shipping_address' => [
                    'first_name' => 'TEST',
                    'last_name' => 'MIDTRANSER',
                    'email' => 'test@midtrans.com',
                    'phone' => '0 8128-75 7-9338',
                    'address' => 'Sudirman',
                    'city' => 'Jakarta',
                    'postal_code' => '12190',
                    'country_code' => 'IDN'
                ]
            ],
            'expiry' => [
                'start_time' => Carbon::now()->addDay()->format('Y-m-d h:i:s +0000'),
                'unit' => 'minutes',
                'duration' => 1
            ],
        ];
//        dd($array);
        $a = MidTrans::pay($array)->send();

        return response()->json($a->data->token);
    }

    public function midtransPay($redeem, $order, $invoice_no, $detail, $request, $product, $customer, $totalAmount)
    {
        if (!$redeem && $order->allow_payment == '1') {
            $array = [
                'transaction_details' => [
                    'order_id' => $order->invoice_no,
                    'gross_amount' => (int)$totalAmount,
                ],
                'item_details' => [
                    'id' => $product->unique_code,
                    'price' => (int)$totalAmount,
                    'quantity' => 1,
                    'name' => $detail->adult . ' x ' . $product->product_name,
                    'brand' => $product->product_name,
                    'category' => $product->first()->category->category_name,
                    'merchant_name' => 'Midtrans'
                ],
                'customer_details' => [
                    'first_name' => $customer->first_name,
                    'last_name' => null,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'billing_address' => [
                        'first_name' => $customer->first_name,
                        'last_name' => null,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'address' => $request->get('address'),
                        'city' => null,
                        'postal_code' => null,
                        'country_code' => null
                    ],
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O', time()),
                    'unit' => 'days',
                    'duration' => 1
                ],
                'enabled_payments' => [$request->payment_method],
            ];

            if ($array['enabled_payments'][0] == 'gopay') {
                $array['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => 'http://gopay.com'
                ];
            }
//            elseif($array['enabled_payments'][0]){
//                $array['bca_va'] = [
//                    'va_number' =>
//                ];
//            }
//        dd($array['expiry']['unit']);
            $midtrans = MidTrans::pay($array)->send();

            $paymentgateway = '';
            if ($request->input('payment_method') == 'indomaret') {
                $paymentgateway = 'Midtrans Indomaret';
            } elseif ($request->input('payment_method') == 'gopay') {
                $paymentgateway = 'Midtrans Gopay';
            } elseif ($request->input('payment_method') == 'bca_va') {
                $paymentgateway = 'Midtrans Virtual Account BCA';
            } elseif ($request->input('payment_method') == 'akulaku') {
                $paymentgateway = 'Midtrans AkuLaku';
            }

            if ($array['expiry']['unit'] === 'day' || $array['expiry']['unit'] === 'days') {
                $expiry = Carbon::parse($array['expiry']['start_time'])->addDays($array['expiry']['duration'])->toDateTimeString();
            } elseif ($array['expiry']['unit'] === 'minute' || $array['expiry']['unit'] === 'minutes') {
                $expiry = Carbon::parse($array['expiry']['start_time'])->addMinutes($array['expiry']['duration'])->toDateTimeString();
            } else {
                $expiry = Carbon::parse($array['expiry']['start_time'])->addHours($array['expiry']['duration'])->toDateTimeString();
            }

            if ($order->booking_type == 'offline') {
                $payment = $order->payment->update([
                    'invoice_no' => $request->invoice_no,
                    'payment_gateway' => $paymentgateway,
                    'reference_number' => null,
                    'token_midtrans' => $midtrans->data->token,
                    'status' => 'PENDING',
                    'amount' => ceil($order->total_amount),
                    'invoice_url' => $midtrans->data->redirect_url,
                    'expiry_date' => $expiry,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'response' => null,
                ]);
            } else {
                $payment = \App\Models\Payment::create([
                    'invoice_no' => $invoice_no,
                    'payment_gateway' => $paymentgateway,
                    'token_midtrans' => $midtrans->data->token,
                    'reference_number' => null,
                    'status' => 'PENDING',
                    'amount' => ceil($order->total_amount),
                    'invoice_url' => $midtrans->data->redirect_url,
                    'expiry_date' => $expiry,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'response' => null,
                ]);
            }

            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
            $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
            if (env('APP_ENV') !== 'local'):
            $product_service->sendWACustomer($invoice_no);
            $product_service->sendWAProvider($invoice_no);
            endif;
            \Log::info('WAProvider from' . HomeCtrl::class . ' line 2873');

            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = $order->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Online Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
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
            $this->sendDiscordNotification($content, 'transaction');
            \DB::commit();

        } elseif ($redeem) {
            $order->update(['status' => 1]);
            $payment = \App\Models\Payment::create([
                'invoice_no' => $invoice_no,
                'status' => 'PAID',
                'payment_gateway' => 'Redeem Voucher',
                'created_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                'updated_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
            ]);
            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer(auth('api')->user()->company->id_company, $invoice_no);
            $product_service->allMailProvider(auth('api')->user()->company->id_company, $invoice_no);
            if (env('APP_ENV')!=='local'):
            $product_service->sendWACustomer($invoice_no);
            $product_service->sendWAProvider($invoice_no);
            endif;
            \Log::info('WAProvider from' . HomeCtrl::class . ' line 2922');
            //                $this->send_invoice(auth('api')->user()->company->id_company, $invoice_no);
            //                $this->send_email_notif(auth('api')->user()->company->id_company, $invoice_no);
            \DB::commit();
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = $order->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Online POS Booking ' . Carbon::now()->format('d M Y H:i:s') . '**';
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

            $this->sendDiscordNotification($content, 'transaction');
        }
    }
}