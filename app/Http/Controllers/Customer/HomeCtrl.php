<?php

namespace App\Http\Controllers\Customer;

use App\Enums\CustomerManualTransferStatus;
use App\Jobs\SendEmail;
use App\Models\CategoryPayment;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyPayment;
use App\Models\Customer;
use App\Models\ListPayment;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Voucher;
use App\Traits\DiscordTrait;
use App\Traits\InsuranceValidationTrait;
use App\Traits\ValidationScheduleTrait;
use Carbon\Carbon;
use Gomodo\Midtrans\MidTrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerManualTransfer;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Traits\CustomInfoRulesTrait;
use App\Models\CustomSchema;
use Mail;
use File;
use Image;
use Storage;

class HomeCtrl extends Controller
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

    /**
     * get detail product
     *
     * @param           $id_product
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detailProduct($id_product, Request $request)
    {
        $product = Product::where(['unique_code' => $id_product, 'id_company' => $request->get('my_company')])->first();
        $orderAds = null;
        if (!$product) {
            msg(trans('notification.product.not_found'), 2);
            return redirect()->route('memoria.home');
        }
        $company = $product->company;
        $product->increment('viewed');
        if ($request->get('klhk')) {
            return view('klhk.customer.products.detail', compact('product', 'company', 'orderAds'));
        }
        return view('customer.products.detail', compact('product', 'company', 'orderAds'));
    }

    /**
     * validation schedule from customer
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateSchedule(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Not permitted'], 403);
        }
        $rule = [
            'product' => [
                'required',
                Rule::exists('tbl_product', 'unique_code')->where('id_company',
                    $request->get('my_company'))->where('status', 1)
            ],
            'schedule_date' => 'required|date_format:Y-m-d',
            'pax' => 'required|min:1'
        ];
        $this->validate($request, $rule);
        $validate = $this->scheduleValidation($request->get('product'), $request->get('schedule_date'),
            $request->get('my_company'), $request->get('pax'));
        return response()->json($validate['result'], $validate['status']);

    }

    /**
     * customer booking
     *
     * @param           $id_product
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function book($id_product, Request $request)
    {
        $product = Product::where('unique_code', $id_product)
            ->where('id_company', $request->get('my_company'))
            ->where('status', 1)
            ->whereHas('schedule', function ($sch) use ($request) {
                $sch->where('start_date', '<=', Carbon::parse($request->get('schedule_date'))->toDateString())
                    ->where('end_date', '>=', Carbon::parse($request->get('schedule_date'))->toDateString());
            })
            ->with('customSchema')
            ->first();
		
        if (!$product) {
            \Session::flash('error', 'Product not Found');
            return redirect()->route('memoria.home');
        }
        $pricing = $this->scheduleValidation($id_product,
            Carbon::parse($request->input('schedule_date'))->toDateString(),
            $product->company->id_company, $request->input('pax'));
        if ($pricing['status'] !== 200) {
            \Session::flash('error', 'Something wrong with calculating price');
            return redirect()->route('memoria.home');
        }
        $data['product'] = $product;
        $data['orderAds'] = null;
        $data['company'] = $product->company;
        $data['departure_date'] = Carbon::parse($request->input('schedule_date'))->toDateString();
        $data['departure_date_format'] = Carbon::parse($request->input('schedule_date'))->format('d M Y');
        $data['payment_list'] = CategoryPayment::with([
            'listPayments' => function ($q) {
                $q->whereStatus(1)->orderBy('name_payment', 'asc');
            }
        ])->orderBy('name_third_party', 'desc')->get();
        $data['departure_date'] = Carbon::parse($request->get('schedule_date'))->toDateString();
        $data['departure_date_format'] = Carbon::parse($request->get('schedule_date'))->format('d M Y');
        $data['pricing'] = $pricing['result']['result'];
        $data['pax'] = $request->get('pax');
        $data['custom_all_participant'] = $product->customSchema->where('fill_type', 'all participant')->count() > 0;
        // Jika ada custom type negara
        if ($product->customSchema->whereIn('type_custom', ['country', 'state', 'city'])->count() > 0) {
            $data['country'] = \App\Models\Country::select('id_country', 'country_name')->get();
        }

        $data['states'] = \App\Models\State::select('id_state', 'state_name', 'state_name_en')
            ->where('id_country', 102)
            ->orderBy('state_name' . (app()->getLocale() == 'en' ? '_en' : ''), 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_state' => $item->id_state,
                    'state_name' => $item->{'state_name' . (app()->getLocale() == 'en' ? '_en' : '')},
                ];
            });

        \JavaScript::put([
            'pricing' => $pricing['result']['result']
        ]);

        if ($request->get('klhk') == 1) {
            return view('klhk.customer.products.book', $data);
        }
        return view('customer.products.book', $data);
    }

    /**\
     * get city by country ID
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityByCountry(Request $request)
    {
        $q = $request->input('q');
        $result = City::with('state')->where('city_name', 'like', '%' . $q . '%')->whereHas('state',
            function ($c) use ($request) {
                $c->where('id_country', $request->country_id);
            });

        $data = [];
        foreach ($result->paginate(10) as $item) {

            $data[] = [
                'id' => $item->id_city,
                'text' => $item->city_name,
                'state' => $item->state->state_name
            ];
        }
        return response()->json([
            'incomplete_results' => false,
            'items' => $data,
            'total_count' => $result->count()
        ]);
    }

    /**
     * customer Pay
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
            'product' => [
                'required',
                Rule::exists('tbl_product', 'unique_code')->where('id_company',
                    $request->get('my_company'))->where('status', 1)
            ],
            'pax' => 'required|numeric|min:0',
            'departure_date' => 'required|date_format:Y-m-d',
            'full_name' => 'required|max:100',
            'phone_number' => 'nullable|numeric|digits_between:6,20',
            'email' => 'required|email|max:100',
            'address' => 'nullable|max:100',
            'country' => 'nullable|exists:tbl_country,id_country',
            'city' => 'required_with:address|exists:tbl_city,id_city',
            'identity_number_type' => 'required|in:ktp,passport',
            'emergency_number' => 'nullable|numeric|digits_between:6,20',
            'payment_list' => 'required'
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
        $custom_rules = $this->generateCustomRules($request->input('product'));
        $rule = array_merge($rule, $custom_rules['rules']);
        $rule = array_merge($rule, $ruleInsurance['rule']);
        $attributes = array_merge($custom_rules['attributes'], $ruleInsurance['attributes'], $attributes);
        $this->validate($request, $rule, $message, $attributes);

        $pricing = $this->scheduleValidation($request->get('product'), $request->get('departure_date'),
            $request->get('my_company'), $request->get('pax'));
        if ($pricing['status'] !== 200) {
            return response()->json([
                'message' => 'Something wrong with calculation ! please try again',
                'detail' => $pricing['message'],
                'errors' => []
            ], 422);
        }

        try {
            \DB::beginTransaction();
            $product = Product::where('unique_code', $request->get('product'))
                ->where('id_company', $request->get('my_company'))
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
                    ->where('id_company', $request->get('my_company'))
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
            $companyPayment = CompanyPayment::where('company_id', $request->get('my_company'))->where('payment_id',
                $listPayment->id)->first();
            if (empty($companyPayment)) {
                return [
                    'oke' => true,
                    'message' => 'Company Payment Not found'
                ];
            }
            $a = ((double)$pricing['result']['result']['grand_total'] + $extra - (double)$discount);
			
			
            if (optional($companyPayment)->charge_to == '1') {
				
                if ($listPayment->type == 'percentage') {
					$fee = ($a/100)* $listPayment->pricing_primary;
                    //$fee = ceil(((100 / (100 - $listPayment->pricing_primary)) * $a) - $a);
                    //$fee_payment = ceil($fee + $listPayment->pricing_secondary);
                } else { //fixed
                    $fee_payment = ceil($listPayment->pricing_primary);
                }

                if ($listPayment->type_secondary != 'percentage' && $listPayment->type == 'percentage') {
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
            if (in_array($listPayment->code_payment, ['virtual_account','credit_card','bca_manual', 'dana', 'ovo', 'linkaja', 'gopay', 'ovo_live', 'alfamart', 'indomaret'])) {
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

            $customer = Customer::where('email', $request->input('email'))->where('id_company',
                $request->get('my_company'))->first();
            if (!$customer) {
                $customer = Customer::create([
                    'id_company' => $request->get('my_company'),
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
                    'id_company' => $request->get('my_company'),
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
            $dataOrder = [
                'invoice_no' => $invoice_no,
                'id_company' => $request->get('my_company'),
                'transaction_type' => 0,
                'id_customer' => $customer ? $customer->id_customer : null,
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
                        'url' => route('memoria.retrieve.data', ['no_invoice' => $invoice_no])
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
                $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
                $product_service->sendWACustomer($invoice_no);
                $product_service->allMailProvider($request->get('my_company'), $invoice_no);
                $product_service->sendWAProvider($invoice_no);
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
                        'url' => route('invoice.bank-transfer', ['invoice' => $invoice_no])
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
                $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
                $product_service->sendWACustomer($invoice_no);
                $product_service->allMailProvider($request->get('my_company'), $invoice_no);
                $product_service->sendWAProvider($invoice_no);
                \Log::info('WAProvider from' . HomeCtrl::class . ' line 789');
//                $this->send_invoice($request->get('my_company'), $invoice_no);
//                $this->send_email_notif($request->get('my_company'), $invoice_no);
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
                        'url' => route('invoice.success', ['no_invoice' => $invoice_no])
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
                    $product_service->allMailProvider($request->get('my_company'), $invoice_no);
                    $product_service->sendWAProvider($invoice_no);
                    \Log::info('WAProvider from' . HomeCtrl::class . ' line 1027');

                    if ($request->input('payment_method') != 'kredivo') {
                        $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
                        $product_service->sendWACustomer($invoice_no);
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

                    //                $this->send_invoice($request->get('my_company'), $invoice_no);
                    //                $this->send_email_notif($request->get('my_company'), $invoice_no);
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
                            $url = route('invoice.' . str_replace('_', '-', $request->input('payment_method')),
                                ['invoice' => $invoice_no]);
                            break;
                        case 'kredivo':
                            $url = $res->redirect_url;
                            break;
                        case 'dana':
                            $url = route('invoice.dana', ['invoice' => $invoice_no]);
                            break;
                        case 'linkaja':
                            $url = route('invoice.linkaja', ['invoice' => $invoice_no]);
                            break;
                        case 'ovo_live':
                            $url = route('invoice.ovo', ['invoice' => $invoice_no]);
                            break;
                        default:
                            $url = route('invoice.bank-transfer', ['invoice' => $invoice_no]);
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
                    $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
                    $product_service->sendWACustomer($invoice_no);
                    $product_service->allMailProvider($request->get('my_company'), $invoice_no);
                    $product_service->sendWAProvider($invoice_no);
                    \Log::info('WAProvider from' . HomeCtrl::class . ' line 1128');
//                $this->send_invoice($request->get('my_company'), $invoice_no);
//                $this->send_email_notif($request->get('my_company'), $invoice_no);
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
                            'url' => route('memoria.retrieve.data', ['no_invoice' => $invoice_no])
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
            $product_service->allMailProvider($request->get('my_company'), $invoice_no);
            $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
            $product_service->sendWACustomer($invoice_no);
            $product_service->sendWAProvider($invoice_no);
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

    /**
     * check voucher valid or not
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVoucher(Request $request)
    {
        $rule = [
            'id_product' => [
                'required',
                Rule::exists('tbl_product', 'id_product')->where('id_company',
                    $request->get('my_company'))->where('status', 1)
            ],
            'voucher_code' => [
                'required',
                Rule::exists('tbl_voucher', 'voucher_code')->where('id_company',
                    $request->get('my_company'))->where('status', '1'),
            ],
            'amount' => 'required|numeric|min:0'
        ];
        $this->validate($request, $rule);
        $product = Product::withoutGlobalScopes()->where('id_product', $request->id_product)->first();
        $voucher = Voucher::where('voucher_code', $request->get('voucher_code'))->where('id_company',
            $request->get('my_company'))->first();

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

    public function allMailCustomer($company, $id)
    {
        $company = \App\Models\Company::find($company);
        $order = \App\Models\Order::find($id);

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
        $from = 'support@' . env('APP_URL');
        $template = null;
        $subject = null;
        $pdf = null;
        //Send Mail INVOICE
        if ($order->status == 0) {
            $subject = "Order Invoice & Itinerary for " . $company->company_name;
            if ($order->booking_type == 'online') {
                $template = 'dashboard.company.order.mail_customer.unpaidbooking';
                $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidBooking';
            } else {
                $template = 'dashboard.company.order.mail.unpaidbookingoffline';
                $pdf = 'dashboard.company.order.mail.pdfUnpaidBookingOffline';
            }
        } elseif ($order->status == 1) {
            $subject = "Booking for " . $company->company_name . " #" . $id;
            if ($order->booking_type == 'online') {
                $template = 'dashboard.company.order.mail_customer.paidbooking';
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = 'dashboard.company.order.mail.paidbookingoffline';
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 2 || $order->status == 3) {
            $subject = $company->company_name . " Tour On Progress #" . $id;
            if ($order->booking_type == 'online') {
                $template = 'dashboard.company.order.mail_customer.paidbooking';
                $pdf = 'dashboard.company.order.mail_customer.pdfPaidBooking';
            } else {
                $template = 'dashboard.company.order.mail.paidbookingoffline';
                $pdf = 'dashboard.company.order.mail.pdfPaidBookingOffline';
            }
        } elseif ($order->status == 8) {
            $subject = $company->company_name . " New Booking Inquiry #" . $id;
            if ($order->booking_type == 'online') {
                $template = $company->active_theme->theme->source . '.booking-email';
                $pdf = $company->active_theme->theme->source . '.booking-email';
            } else {
                $template = $company->active_theme->theme->source . '.booking-offline-email';
                $pdf = $company->active_theme->theme->source . '.booking-offline-email';
            }
        } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
            $subject = $company->company_name . " #" . $id . " Booking Canceled";
            if ($order->booking_type == 'online') {
                $template = 'dashboard.company.order.mail_customer.cancelbooking';
                $pdf = 'dashboard.company.order.mail_customer.pdfCancelBooking';
            } else {
                $template = 'dashboard.company.order.mail.cancelbookingoffline';
                $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
            }
        } else {
            $subject = $company->company_name . " Booking Completed #" . $id;
            if ($order->booking_type == 'online') {
                $template = $company->active_theme->theme->source . '.booking-email';
                $pdf = $company->active_theme->theme->source . '.booking-email';
            } else {
                $template = $company->active_theme->theme->source . '.booking-offline-email';
                $pdf = $company->active_theme->theme->source . '.booking-offline-email';
            }
        }

        $data_pdf = \PDF::setPaper('A4')->loadView($pdf, $data);
        \Mail::send($template, $data, function ($message) use ($data_pdf, $data, $to, $from, $subject) {
            $message->to($to)->subject($subject);
            $message->attachData($data_pdf->output(), $subject . '.pdf');
            $message->from($from, 'Admin Gomodo');
        });
    }

    public function allMailProvider($company, $id)
    {
        $company = \App\Models\Company::find($company);
        $order = \App\Models\Order::find($id);
        $data = ['company' => $company, 'order' => $order];

        $to = $order->company->email_company;
        $from = 'support@' . env('APP_URL');
        $template = null;
        $subject = null;
        $pdf = null;
        if (!empty($company->email_company) && $order) {
            if ($order->status == 1) {
                $subject = "New Confirmed Booking #" . $id;
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.paidprovider';
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = 'dashboard.company.order.mail.paymentsuccessnotif';
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 0) {
                $subject = "Unpaid Booking #" . $id;
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.unpaidprovider';
                    $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidProvider';
                } else {
                    $template = 'dashboard.company.order.mail.unpaidprovider';
                    $pdf = 'dashboard.company.order.mail.pdfUnpaidProvider';
                }
            } elseif ($order->status == 2 || $order->status == 3) {
                $subject = "Tour On Process #" . $id;
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.paidprovider';
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = 'dashboard.company.order.mail.paymentsuccessnotif';
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 8) {
                $subject = "New Booking Inquiry " . $id;
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.emailnotif';
                    $pdf = 'dashboard.company.order.emailnotif';
                } else {
                    $template = 'dashboard.company.order.emailnotiforderoffline';
                    $pdf = 'dashboard.company.order.emailnotiforderoffline';
                }
            } elseif ($order->status == 6 || $order->status == 5 || $order->status == 7) {
                $subject = "#" . $id . " Booking Canceled";
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.cancelprovider';
                    $pdf = 'dashboard.company.order.mail_customer.pdfCancelProvider';
                } else {
                    $template = 'dashboard.company.order.mail.cancelbookingoffline';
                    $pdf = 'dashboard.company.order.mail.pdfCancelBookingOffline';
                }
            } else {
                $subject = "#" . $id . " Booking Complete!";
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.emailnotif';
                    $pdf = 'dashboard.company.order.emailnotif';
                } else {
                    $template = 'dashboard.company.order.emailnotiforderoffline';
                    $pdf = 'dashboard.company.order.emailnotiforderoffline';
                }
            }

            $data_pdf = \PDF::setPaper('A4')->loadView($pdf, $data);
            \Mail::send($template, $data, function ($message) use ($data_pdf, $data, $to, $from, $subject) {
                $message->to($to)->subject($subject);
                $message->attachData($data_pdf->output(), $subject . '.pdf');
                $message->from($from, 'Admin Gomodo');
            });
        }
    }

    /**
     * send invoice to customer
     *
     * @param $company
     * @param $id
     *
     * @throws \Throwable
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

    /**
     * send email notif to company
     *
     * @param $company
     * @param $id
     *
     * @throws \Throwable
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
                $subject = "New Confirmed Booking #" . $id;
                if ($order->booking_type == 'online') {
                    $template = view('dashboard.company.order.mail_customer.paidprovider',
                        $email_view_data)->render();
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif',
                        $email_view_data)->render();
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
                    $template = view('dashboard.company.order.mail.paymentsuccessnotif',
                        $email_view_data)->render();
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
                    $template = view('dashboard.company.order.mail.cancelbookingoffline',
                        $email_view_data)->render();
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


    /**
     * show payment virtual account
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payByVirtualAccount(Request $request)
    {
        $company = \App\Models\Company::find($request->get('my_company'));
        $orderAds = null;
        return view('customer.products.payment.virtual_account', compact('company', 'orderAds'));
    }

    /**
     * post data to xendit
     *
     * @param $url
     * @param $data_string
     *
     * @return bool|string
     */
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

    /**
     * get data from Xendit
     *
     * @param           $invoice
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getDataXendit($invoice, Request $request)
    {
        $order = \App\Models\Order::where('invoice_no', $invoice)->first();
        $company = \App\Models\Company::find($request->get('my_company'));
        $orderAds = null;
        if ($order) {
            if ($company && $order->id_company == $company->id_company) {
                if ($order->status == '1') {
                    return redirect()->route('invoice.success', ['no_invoice' => $order->invoice_no]);
                }
                if ($order->payment_list == 'Midtrans Payment') {
                    $res = $order->payment->response_midtrans;
                    $payment = $order->payment;
                    $product = $order->order_detail->product;
                    if (optional($order->payment)->payment_gateway == 'Midtrans Indomaret') {
                        return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.midtrans-indomaret', compact('order', 'orderAds', 'product', 'company', 'res', 'payment'));
                    } elseif (optional($order->payment)->payment_gateway == 'Midtrans Gopay') {
                        return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.midtrans-gopay', compact('order', 'orderAds', 'product', 'company', 'res', 'payment'));
                    } elseif (optional($order->payment)->payment_gateway == 'Midtrans AkuLaku') {
                        return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.akulaku',
                            compact('order', 'orderAds', 'product', 'company', 'res', 'payment'));
                    } else {
                        return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.midtrans-virtual-account', compact('order', 'orderAds', 'product', 'company', 'res', 'payment'));
                    }

                } elseif ($order->payment_list == 'COD') {
                    if ($order->payment && $order->payment->payment_gateway == 'Cash On Delivery') {
                        return redirect()->route('invoice.success', ['no_invoice' => $order->invoice_no]);
                    }
                } elseif ($order->payment_list == 'Manual Transfer') {
                    $res = $order->payment->response;
                    $product = $order->order_detail->product;
                    if ($order->payment->payment_gateway == 'Manual Transfer BCA') {
                        return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.bca_manual', compact('order', 'orderAds', 'product', 'company', 'res'));
                    }
                } else {
                    if ($order->payment) {
                        if ($order->payment->expiry_date < Carbon::now()->toDateTimeString()) {
                            return redirect()->route('memoria.retrieve.data', ['no_invoice' => $invoice]);
                        }
                        $res = $order->payment->response;
                        $product = $order->order_detail->product;
                        // Kredivo
                        if (optional($order->payment)->payment_gateway == 'Xendit Kredivo') {
                            if ($order->payment->status == 'INCOMPLETE') {
                                $order->payment->update(['status' => 'PENDING']);
                            }
                            return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.kredivo',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }

                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit Virtual Account')) {
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.virtual_account',
                                    compact('company', 'res', 'product', 'order', 'orderAds'));
                            }
                            return view('customer.products.payment.virtual_account',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }

                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit Virtual Account OVO')) {
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.ovo-bank-transfer',
                                    compact('company', 'res', 'product', 'order', 'orderAds'));
                            }
                            return view('customer.products.payment.ovo-bank-transfer',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit Virtual Account OVO')) {
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.ovo-bank-transfer',
                                    compact('company', 'res', 'product', 'order', 'orderAds'));
                            }
                            return view('customer.products.payment.ovo-bank-transfer',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit Alfamart')) {
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.alfamart',
                                    compact('company', 'res', 'product', 'order', 'orderAds'));
                            }
                            return view('customer.products.payment.alfamart',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit OVO')) {
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.ovo',
                                    compact('company', 'res', 'product', 'order', 'orderAds'));
                            }
                            return view('customer.products.payment.ovo',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit DANA')) {
                            return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.dana',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && ($order->payment->payment_gateway == 'Xendit LinkAja')) {
                            return view(($company->is_klhk == 1 ? 'klhk.' : '') . 'customer.products.payment.linkaja',
                                compact('company', 'res', 'product', 'order', 'orderAds'));
                        }
                        if ($order->payment && $order->payment->payment_gateway == 'Xendit Credit Card') {
                            //                    return redirect($order->payment->invoice_url . '#credit-card');
                            $years = [];
                            for ($i = 0; $i < 11; $i++) {
                                $years = Arr::add($years, Carbon::now()->addYear($i)->format('Y'),
                                    Carbon::now()->addYear($i)->format('y'));
                            }
                            $months = [];
                            for ($i = 1; $i <= 12; $i++) {
                                if ($i < 10) {
                                    $months = array_add($months, '0' . $i, '0' . $i);
                                } else {
                                    $months = array_add($months, strval($i), (string)$i);
                                }

                            }
                            if ($company->is_klhk == 1) {
                                return view('klhk.customer.products.payment.credit-card',
                                    compact('company', 'res', 'product', 'order', 'orderAds', 'years', 'months'));
                            }
                            return view('payment.credit-card',
                                compact('company', 'res', 'product', 'order', 'orderAds', 'years', 'months'));
                        }
                    }
                    \Session::flash('failed', 'Wrong payment type');
                    return redirect('/');
                }
                \Session::flash('failed', 'Wrong Company');
                return redirect('/');
            }
            \Session::flash('failed', 'No Order Found');
            return redirect('/');

        }
    }

    /**
     * paying for E Invoicing
     *
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function payOffline($request)
    {
        $rules = [
            'payment_method' => 'required|in:credit_card,virtual_account,alfamart,ovo,indomaret,gopay,alfamart_midtrans,ovo_live,dana,linkaja,bca_va,kredivo,bca_manual,akulaku',
            'invoice_no' => Rule::exists('tbl_order_header', 'invoice_no')
                ->where('id_company', $request->get('my_company'))
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
                                'url' => route('invoice.bank-transfer', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit Credit Card') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('invoice.credit-card', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit Alfamart') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('invoice.alfamart', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit OVO') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('invoice.ovo', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit DANA') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('invoice.dana', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if ($order->payment->payment_gateway == 'Xendit LinkAja') {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('invoice.linkaja', ['invoice' => $request->invoice_no])
                            ]
                        ]);
                    }
                    if (in_array($order->payment->payment_gateway, ['Midtrans Indomaret', 'Midtrans Gopay', 'Midtrans Virtual Account BCA', 'Midtrans AkuLaku'])) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Redirecting to payment page',
                            'data' => [
                                'invoice' => $request->invoice_no,
                                'url' => route('check-invoice.invoice.midtrans', ['invoice' => $request->invoice_no])
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
                        $request->get('my_company'))->first();
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
                $companyPayment = CompanyPayment::where('company_id', $request->get('my_company'))->where('payment_id', $listPayment->id)->first();

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
                // if (in_array($listPayment->code_payment, ['credit_card', 'gopay','indomaret','dana', 'linkaja','bca_va'])) {
                //     // fee to customer
                //     if (in_array($listPayment->code_payment, ['credit_card', 'dana', 'linkaja'])){
                //         if ($listPayment->type == 'percentage') {
                //             $fee = ceil(((100 / (100 - $listPayment->pricing_primary)) * $order->total_amount) - $order->total_amount);
                //             $fee_credit_card = ceil($fee + $listPayment->pricing_secondary);
                //         } else {
                //             $fee_credit_card = ceil($listPayment->pricing_primary + $listPayment->pricing_secondary);
                //         }
                //         if (in_array($listPayment->code_payment, ['dana','linkaja'])){
                //             $fee_payment = $fee_credit_card;
                //             $fee_credit_card = 0;
                //         }
                //     } else {
                //         if ($listPayment->type == 'percentage') {
                //             $fee_payment = ceil((($listPayment->pricing_primary / 100) * $order->total_amount) + $listPayment->pricing_secondary);
                //         } else {
                //             $fee_payment = ceil($listPayment->pricing_primary + $listPayment->pricing_secondary);
                //         }
                //     }
                // } else {
                //     $fee_credit_card = 0;
                //     $fee_payment = 0;
                // }

                $totalAmount = (((double)$order->total_amount - (double)$discount) + $fee_credit_card) + $fee_payment;
                if (optional($companyPayment)->charge_to == '0' || optional($companyPayment)->charge_to == null) {
                    $totalAmount = ceil(($totalAmount - $fee_credit_card) - $fee_payment);
                }

                if (in_array($request->input('payment_method'), ['alfamart', 'indomaret'])) {
                    if ($totalAmount > 5000000) {
                        return apiResponse(400, \trans('booking.validation.alfamart'));
                    }
                    if ($totalAmount < 10000){
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
                            'url' => route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no])
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
                            'url' => route('invoice.bank-transfer', ['invoice' => $order->invoice_no])
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
                        $product_service->allMailProvider($request->get('my_company'), $order->invoice_no);
                        $product_service->allMailCustomer($request->get('my_company'), $order->invoice_no);
                        $product_service->sendWACustomer($order->invoice_no);
                        $product_service->sendWAProvider($order->invoice_no);
                        \Log::info('WAProvider from' . HomeCtrl::class . ' line 2274');

                        //                    $this->send_invoice($request->get('my_company'), $order->invoice_no);
                        //                    $this->send_email_notif($request->get('my_company'), $order->invoice_no);
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
                                'url' => route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no])
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
                    }  elseif ($request->input('payment_method') == 'dana') {
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
                                'url' => route('invoice.ovo', ['invoice' => $order->invoice_no])
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
                            $url = route('invoice.' . str_replace('_', '-', $request->input('payment_method')),
                                ['invoice' => $request->invoice_no]);
                            break;
                        case 'kredivo':
                            $url = $res->redirect_url;
                            break;
                        case 'dana':
                            $url = route('invoice.dana', ['invoice' => $request->invoice_no]);
                            break;
                        case 'linkaja':
                            $url = route('invoice.linkaja', ['invoice' => $request->invoice_no]);
                            break;
                        case 'ovo_live':
                            $url = route('invoice.ovo', ['invoice' => $request->invoice_no]);
                            break;
                        default:
                            $url = route('invoice.bank-transfer', ['invoice' => $request->invoice_no]);
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
                    // if ($request->input('payment_method') == 'credit_card') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.credit-card', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } elseif ($request->input('payment_method') == 'alfamart') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.alfamart', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } elseif ($request->input('payment_method') == 'ovo') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.ovo', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } elseif ($request->input('payment_method') == 'dana') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.dana', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } elseif ($request->input('payment_method') == 'linkaja') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.linkaja', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } elseif ($request->input('payment_method') == 'ovo_live') {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.ovo', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // } else {
                    //     return response()->json([
                    //         'status' => 200,
                    //         'message' => 'Redirecting to payment page',
                    //         'data' => [
                    //             'invoice' => $request->invoice_no,
                    //             'url' => route('invoice.bank-transfer', ['invoice' => $request->invoice_no])
                    //         ]
                    //     ]);
                    // }
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

    /**
     * check order paid or not
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDataOrder(Request $request)
    {
        $order = \App\Models\Order::where('invoice_no', $request->get('invoice_no'))->where('id_company',
            $request->get('my_company'))->first();
        if ($order) {
            if ($order->status == '0') {
                if ($order->payment_list == 'Xendit Payment') {
                    if ($order->payment && in_array($order->payment->payment_gateway, [
                            'Xendit Virtual Account', 'Xendit Virtual Account OVO', 'Xendit Credit Card', 'Xendit Alfamart'])) {
                        $res = $order->payment->response;
                        if (Carbon::parse($res['expiry_date'])->toDateTimeString() < Carbon::now()->toDateTimeString()) {
                            $redirect = route('memoria.retrieve.data', ['no_invoice' => $res->invoice_no]);
                            return apiResponse(302, 'status changed', ['redirect' => $redirect]);
                        }
                        return apiResponse(200, 'ok');
                    } elseif ($order->payment && in_array($order->payment->payment_gateway, ['Xendit OVO', 'Xendit DANA', 'Xendit LinkAja', 'Xendit Kredivo'])) {
                        $res = $order->payment->response;
                        if (Carbon::parse($order->payment->expiry_date)->toDateTimeString() < Carbon::now()->toDateTimeString()) {
                            $redirect = route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no]);
                            return apiResponse(302, 'status changed', ['redirect' => $redirect]);
                        }
                        return apiResponse(200, 'ok');
                    }
                } elseif ($order->payment_list == 'Midtrans Payment') {
                    $res = $order->payment->response_midtrans;
                    if ($order->payment && in_array($order->payment->payment_gateway, ['Midtrans Indomaret', 'Midtrans Gopay', 'Midtrans Virtual Account BCA'])) {
                        if (Carbon::parse($order->payment->expiry_date)->toDateTimeString() < Carbon::now()->toDateTimeString()) {
                            $redirect = route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no]);
                            return apiResponse(302, 'status changed', ['redirect' => $redirect]);
                        }
                    }
                    return apiResponse(200, 'ok');
                } elseif ($order->payment_list == 'Manual Transfer') {
                    $res = $order->payment->response;
                    if (optional($order->customer_manual_transfer) && in_array(optional($order->customer_manual_transfer)->status, ['need_confirmed', 'customer_reupload'])) {
                        $redirect = route('invoice.success', ['no_invoice' => $order->invoice_no]);
                        return apiResponse(200, 'status changed', ['redirect' => $redirect]);
                    }
                    if ($order->payment && $order->payment->payment_gateway == 'Manual Transfer BCA') {
                        if (Carbon::parse($order->payment->expiry_date)->toDateTimeString() < Carbon::now()->toDateTimeString()) {
                            $redirect = route('memoria.retrieve.data', ['no_invoice' => $order->invoice_no]);
                            return apiResponse(302, 'status changed', ['redirect' => $redirect]);
                        }
                    }
                    return apiResponse(200, 'ok');
                }
            } else {
                if ($order->payment && in_array($order->payment->payment_gateway, [
                        'Xendit Virtual Account',
                        'Xendit Virtual Account OVO',
                        'Xendit Alfamart',
                        'Xendit OVO',
                        'Xendit DANA',
                        'Xendit LinkAja',
                        'Xendit Credit Card',
                        'Midtrans Indomaret',
                        'Midtrans Gopay',
                        'Midtrans Alfamart',
                        'Midtrans Virtual Account BCA',
                        'Xendit Kredivo',
                        'Manual Transfer BCA'
                    ])) {
                    $redirect = route('invoice.success', ['no_invoice' => $order->invoice_no]);
                } else {
                    $redirect = route('invoice.redeem-voucher', ['invoice' => $order->invoice_no]);
                }
                return apiResponse(302, 'status changed', ['redirect' => $redirect]);
            }
        }
        return apiResponse(404, 'order not found', ['redirect' => url('/')]);
    }

    public function checkMidtrans(Request $request)
    {
        $data = json_encode($request->result, true);
        $res = json_decode($data);
        $order = Order::where('invoice_no', $request->invoice_no)->whereHas('payment', function ($q) {
            $q->where('status', 'PENDING');
        })->first();

        if (optional($res)->status_code != '201') {
            $order->update([
                'status' => 7
            ]);
            $order->payment->update([
                'response' => $res,
                'status' => 'CANCEL BY SYSTEM'
            ]);
            return apiResponse($res->status_code, \trans('midtrans_notification.' . $res->status_code));
        } else {
            $order->payment->update([
                'response' => $res,
                //            'reference_number' => $res['transaction_id']
            ]);

            if ($order->payment->payment_gateway == 'Midtrans Indomaret') {
                return response()->json([
                    'message' => 'Oke',
                    'data' => [
                        'invoice' => $order->invoice_no,
                        'url' => route('invoice.indomaret', ['invoice' => $order->invoice_no])
                    ]
                ], 200);
            } elseif ($order->payment->payment_gateway == 'Midtrans Gopay') {
                return response()->json([
                    'message' => 'Oke',
                    'data' => [
                        'invoice' => $order->invoice_no,
                        'url' => route('invoice.gopay', ['invoice' => $order->invoice_no])
                    ]
                ], 200);
            } elseif ($order->payment->payment_gateway == 'Midtrans Virtual Account BCA') {
                return response()->json([
                    'status' => 200,
                    'message' => 'Oke',
                    'data' => [
                        'invoice' => $order->invoice_no,
                        'url' => route('invoice.midtrans-virtual-account', ['invoice' => $order->invoice_no])
                    ]
                ]);
            }
        }

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
            $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
            $product_service->sendWACustomer($invoice_no);
            $product_service->allMailProvider($request->get('my_company'), $invoice_no);
            $product_service->sendWAProvider($invoice_no);
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
            $product_service->allMailCustomer($request->get('my_company'), $invoice_no);
            $product_service->sendWACustomer($invoice_no);
            $product_service->allMailProvider($request->get('my_company'), $invoice_no);
            $product_service->sendWAProvider($invoice_no);
            \Log::info('WAProvider from' . HomeCtrl::class . ' line 2922');
            //                $this->send_invoice($request->get('my_company'), $invoice_no);
            //                $this->send_email_notif($request->get('my_company'), $invoice_no);
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

    public function notificationMidtrans(Request $request)
    {
        $data = $request->all();
        dd($data['store']);
        return response()->json(MidTrans::notification($data)->getNotification());
    }

    public function kredivoCallback(Request $request)
    {
        $json = json_decode($request->getContent());

        try {
            $status = [
                'settlement' => [1, 'PENDING'],
                'pending' => [0, 'PENDING'],
                'deny' => [7, 'DENY'],
                'cancel' => [5, 'CANCEL'],
                'expire' => [7, 'EXPIRED']
            ];

            $order = Order::where('invoice_no', $json->external_id)->first();

            if ($order->payment->status == $status[$json->transaction_status][1] && $order->status == $status[$json->transaction_status][0]) {
                return [
                    'ok' => true,
                    'msg' => 'No update'
                ];
            }

            $order->update(['status' => $status[$json->transaction_status][0]]);

            $payment = \App\Models\Payment::where('invoice_no', $json->external_id)->first();
            $payment->update([
                'reference_number' => $json->order_id,
                //'settlement_on'     => $status[$json->transaction_status][0] == 1 ? now() : null,
                'status' => $status[$json->transaction_status][1]
            ]);

            $payment->kredivo()->update([
                'installment_duration' => $json->payment_type,
                'response' => json_encode($json)
            ]);

            if (in_array($status[$json->transaction_status][0], [0, 1])) {
                $product_service = app('\App\Services\ProductService');
                $product_service->allMailCustomer($order->id_company, $json->external_id);
                $product_service->sendWACustomer($json->external_id);
                $product_service->allMailProvider($order->id_company, $json->external_id);
                $product_service->sendWAProvider($json->external_id);
                \Log::info('WAProvider from' . HomeCtrl::class . ' line 3106');
            }

            if ($status[$json->transaction_status][0] == 1) {
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $order->company;
                $loc = \Stevebauman\Location\Facades\Location::get($ip);
                $http = env('HTTPS', false) == true ? 'https://' : 'http://';
                $content = '**New PAID & Waiting for Settlement Online Booking ' . $order->invoice_no . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
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

            return [
                'ok' => true
            ];
        } catch (\Exception $e) {
            return response([
                'ok' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

    public function ewalletCallback(Request $request)
    {
        $callback = json_decode($request->getContent());
        
        try {
            $order = Order::where('invoice_no', $callback->external_id)->first();
            
            switch ($order->payment->payment_gateway) {
                case 'Xendit DANA':
                    $code_payment = 'dana';
                    break;
                case 'Xendit LinkAja' :
                    $code_payment = 'linkaja';
                    break;
                case 'Xendit OVO' :
                    $code_payment = 'ovo_live';
                    break;
                default:
                    $code_payment = '';
            }
            $list = ListPayment::where('code_payment', $code_payment)->first();
            $settlement = $list->settlement_duration;
            if (empty($list)) {
                $settlement = 2;
            }

            if ($code_payment == 'ovo_live' && optional($callback)->business_id == optional($order->payment)->reference_number) {
                if ($callback->status != 'COMPLETED') {
                    return [
                        'oke' => true,
                        'message' => 'payment gagal'
                    ];
                }
                $order->payment->update([
                    'status' => 'PENDING',
                    'received_amount' => $callback->amount,
                    'reference_number' => $callback->business_id,
                    'settlement_on' => now()->addDays($settlement)->toDateTimeString(),
                    'response' => json_encode($callback)
                ]);

            } else {
                if ($callback->payment_status == 'EXPIRED' || $callback->payment_status == 'FAILED') {
                    $order->update([
                        'status' => 7
                    ]);
    
                    $order->payment->update([
                        'status' => 'CANCEL BY SYSTEM'
                    ]);
    
                    return [
                        'oke' => true,
                        'message' => 'payment gagal'
                    ];
                }
                $order->update([
                    'status' => 1
                ]);
    
                $order->payment->update([
                    'status' => 'PENDING',
                    'received_amount' => $callback->amount,
                    'reference_number' => $callback->business_id,
                    'settlement_on' => now()->addDays($settlement)->toDateTimeString(),
                    'response' => json_encode($callback)
                ]);
            }

            if ($order->status == '1'){
                $product_service = app('\App\Services\ProductService');
                $product_service->allMailCustomer($order->id_company, $order->invoice_no);
                $product_service->sendWACustomer($order->invoice_no);
                $product_service->allMailProvider($order->id_company, $order->invoice_no);
                $product_service->sendWAProvider($order->invoice_no);
                \Log::info('WAProvider from' . HomeCtrl::class . ' line 3279');
    
                $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                $newCompany = $order->company;
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
    
                return [
                    'oke' => true,
                    'message' => 'Payment Sukses'
                ];
            } else {
                return [
                    'oke' => true,
                    'message' => 'Payment gagal'
                ];
            }

        } catch (\Exception $e) {
            return response([
                'oke' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function confirmTransfer(Request $request)
    {
        $messages = [
            'no_rekening.required' => trans('custom_validation.no_rekening_required'),
            'bank_name.required' => trans('custom_validation.bank_name_required'),
            'upload_document.required' => trans('custom_validation.upload_document_required'),
        ];
        
        $this->validate($request, [
            'no_rekening' => 'required',
            'bank_name' => 'required',
            'upload_document' => 'required'
        ], $messages);


        try {
            \DB::beginTransaction();
            $order = Order::where('invoice_no', $request->get('invoice_no'))->first();

            if (empty($order->customer_manual_transfer)) {
                if ($request->hasFile('upload_document')) {
                    $path = 'uploads/manual-transfer';
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $source = $request->file('upload_document');
                    $name = 'transfer-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                    if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                        $upload_image = Storage::url($path . '/' . $name);
                    }
                }

                $order->customer_manual_transfer()->create([
                    'bank_name' => $request->get('bank_name'),
                    'no_rekening' => $request->get('no_rekening'),
                    'upload_document' => $upload_image,
                    'status' => CustomerManualTransferStatus::StatusNeedConfirmation
                ]);
            } else {
                if ($request->hasFile('upload_document')) {
                    $path = 'uploads/manual-transfer';
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $source = $request->file('upload_document');
                    $name = 'transfer-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                    if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                        \File::delete(public_path($order->customer_manual_transfer->upload_document));
                        $upload_image = Storage::url($path . '/' . $name);
                    }
                }

                $order->customer_manual_transfer->update([
                    'bank_name' => $request->get('bank_name'),
                    'no_rekening' => $request->get('no_rekening'),
                    'upload_document' => $upload_image,
                    'status' => CustomerManualTransferStatus::StatusCustomerReupload
                ]);
            }

            \DB::commit();

            // $data = ['order' => $order];
            // $subject = 'Awaiting confirmation of payment receipt';
            // $template = view('mail.manual-transfer.customer-upload', $data)->render();
            // dispatch(new SendEmail($subject, $order->company->email_company, $template));

            $product_service = app('\App\Services\ProductService');
            $product_service->allMailCustomer($request->get('my_company'), $order->invoice_no);
            $product_service->allMailProvider($request->get('my_company'), $order->invoice_no);
            $product_service->sendWAProvider($order->invoice_no);
            \Log::info('WAProvider from' . HomeCtrl::class . ' line 3391');
            $product_service->sendWACustomer($order->invoice_no);

            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = $order->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Transaction Need Confirmation BCA Manual Transfer ' . Carbon::now()->format('d M Y H:i:s') . '**';
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
            $content .= "Check Order     : " . $http . env('APP_URL') . '/back-office/transaction/' . $order->invoice_no . '/detail' . "\n";
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
                'message' => 'New Order Unconfirm',
                'data' => [
                    'invoice' => $order->invoice_no,
                    'url' => route('invoice.success', ['no_invoice' => $order->invoice_no])
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return apiResponse(500, __('general.whoops'), getException($e));
        }

    }


}
