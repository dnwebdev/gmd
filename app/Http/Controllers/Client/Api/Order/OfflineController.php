<?php

namespace App\Http\Controllers\Client\Api\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DiscordTrait;

class OfflineController extends Controller
{
    use DiscordTrait;
    public function create(Request $request)
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
            'discount_amount_type' => 'nullable|in:percentage,fixed',
            'discount_amount' => 'nullable|numeric|min:1',
            'full_name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'nullable|numeric|digits_between:6,20',
            'important_notes' => 'nullable',
            'allow_credit_card'=>'nullable|in:0,1',
            'callback_url'=>'required|url'
        ];

        if ($request->input('discount_amount_type') == 'percentage') {
            $rules['discount_amount'] .= '|max:100';
        }

        $this->validate($request, $rules);
        $unique = str_slug('OFFLINE '.date('YmdHis').' '.generateRandomString(5));
        while (Product::where('unique_code', $unique)->first()) {
            $unique = str_slug('OFFLINE '.date('YmdHis').' '.generateRandomString(5));
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
            return apiResponse(400, 'Minimum is 10000');
        }
        try {
            \DB::beginTransaction();
            $product = new Product();
            $product->id_company = auth('api_client')->user()->company->id_company;
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
            $product->created_by = auth('api_client')->user()->company->id_company;
            $product->updated_by = auth('api_client')->user()->company->id_company;
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
                    'id_company' => auth('api_client')->user()->company->id_company,
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
                    'id_company' => auth('api_client')->user()->company->id_company,
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
                'id_company' => auth('api_client')->user()->company->id_company,
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
//            $product_service->allMailCustomer(auth('api_client')->user()->company->id_company, $invoice_no);
            $product_service->allMailProvider(auth('api_client')->user()->company->id_company, $invoice_no);
            $product_service->sendWAProvider( $invoice_no);
            \Log::info('WAProvider from'.OfflineController::class.' line 262');
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = auth('api_client')->user()->company;
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
}
