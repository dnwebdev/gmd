<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;
use Kayiz\Woowa;

class ProductService
{

    var $company = 0;

    /**
     * process function validation schedule
     *
     * @param mixed $id_company
     *
     * @return void
     */
    public function validate_schedule($id_company)
    {
        $this->company = $id_company;

        $request = app('\App\Http\Requests\ValidateSchedule');
        $product = $request->get('product');
        $schedule = date('Y-m-d', strtotime($request->get('schedule')));

        $product = \App\Models\Product::where(['id_product' => $product, 'id_company' => $this->company])
            ->whereHas('schedule', function ($q) use ($schedule) {
                $q->whereRaw("'" . $schedule . "' between `start_date` and `end_date`");
            })->first();

        if ($product) {
            $num_order = Order::where('status', '<', 4)
                ->whereHas('order_detail', function ($q) use ($product) {
                    $q->where('id_product', $product->id_product);
                })->count();

            $num_people = Order::leftJoin('tbl_order_detail', function ($join) {
                $join->on('tbl_order_header.invoice_no', '=', 'tbl_order_detail.invoice_no');
            })
                ->where('status', '<', 4)
                ->whereHas('order_detail', function ($q) use ($product) {
                    $q->where('id_product', $product->id_product);
                })->selectRaw(" SUM(adult) as total ")->first();

            $purchased = Order::leftJoin('tbl_order_detail', function ($join) {
                $join->on('tbl_order_header.invoice_no', '=', 'tbl_order_detail.invoice_no');
            })
                ->where('status', '>', 0)
                ->whereHas('order_detail', function ($q) use ($product) {
                    $q->where('id_product', $product->id_product);
                })->selectRaw(" SUM(adult) as total ")->first();

            $max_stock = $product->max_people ? $product->max_people : 9999;
            if ($product->max_people == 0 || ($num_people->total < $max_stock)) {
                //Cek Minimum Notice
                $notice = date('Y-m-d', strtotime('+' . $product->minimum_notice . ' days'));
                if ($notice <= $schedule) {
                    $data_pricing = json_decode($this->get_total_amount($this->company));
                    $stock = (($max_stock) - ($num_people->total)) >= 0 ? ($max_stock - $num_people->total) : 0;
                    $purchased = $purchased->total ? $purchased->total : 0;
                    if ($data_pricing->status == 200) {
                        return json_encode([
                            'status' => 200, 'message' => 'OK', 'data' => $data_pricing->data, 'stock' => ($stock),
                            'booked' => ($num_people->total), 'purchased' => $purchased
                        ]);
                    } else {
                        return json_encode($data_pricing);
                    }

                } else {
                    if (app()->getLocale() == 'id') {
                        return json_encode([
                            'status' => 403, 'message' => 'Minimal Order sebelum ' . $product->minimum_notice . ' hari'
                        ]);
                    }
                    return json_encode([
                        'status' => 403, 'message' => 'Minimum Notice is ' . $product->minimum_notice . ' days'
                    ]);
                }
            } else {
                if (app()->getLocale() == 'id') {
                    return json_encode(['status' => 403, 'message' => 'Jadwal Pemesanan sudah penuh']);
                }
                return json_encode(['status' => 403, 'message' => 'Schedule is full']);
            }

        } else {
            if (app()->getLocale() == 'id') {
                return json_encode(['status' => 403, 'message' => 'Tanggal yang dipilih tidak sesuai']);
            }
            return json_encode(['status' => 404, 'message' => 'The chosen dates are not suitable']);
        }
    }


    /**
     * process function get total amount
     *
     * @param mixed $id_company
     *
     * @return void
     */
    public function get_total_amount($id_company)
    {
        $this->company = $id_company;
        $request = app('\App\Http\Requests\GetPriceFormRequest');

        $product = $request->get('product');

        $adult = $request->get('adult');
        $children = $request->get('children') ? $request->get('children') : 0;
        $infant = $request->get('infant') ? $request->get('infant') : 0;

        $product = \App\Models\Product::where(['id_company' => $this->company, 'id_product' => $product])->first();

        if (!$product) {
            if (app()->getLocale() == 'id') {
                return json_encode(['status' => 404, 'message' => 'Produk tidak ditemukan']);
            }
            return json_encode(['status' => 404, 'message' => 'Product not found']);
        } elseif (($adult + $children + $infant) < $product->min_people) {
            if (app()->getLocale() == 'id') {
                return json_encode(['status' => 404, 'message' => 'Minimal pemesanan ' . $product->min_people]);
            }
            return json_encode(['status' => 400, 'message' => 'Minimum Order is ' . $product->min_people]);
        }

        $product_pricing = \App\Models\ProductPrice::where(['id_product' => $product->id_product])->get();
        $arr = $product_pricing->toArray();

        $total_price = 0;
        $adt_price = false;
        $chd_price = false;
        $inf_price = false;

        $t_adt_price = 0;
        $t_chd_price = 0;
        $t_inf_price = 0;


        foreach ($arr as $price) {
            $price_from = $price['price_from'] ? $price['price_from'] : 0;
            $price_until = $price['price_until'] ? $price['price_until'] : 999999;

            if ($price['price_type'] == 1 && $adult >= $price_from && $adult <= $price_until) {
                $adt_price = true;
                $t_adt_price = $adult * $price['price'];
                $total_price += $t_adt_price;
                break;
            }
        }

        foreach ($arr as $price) {
            $price_from = $price['price_from'] ? $price['price_from'] : 0;
            $price_until = $price['price_until'] ? $price['price_until'] : 999999;

            if ($price['price_type'] == 2 && $children >= $price_from && $children <= $price_until) {
                $chd_price = true;
                $t_chd_price = $children * $price['price'];
                $total_price += $t_chd_price;
                break;
            }
        }

        $total_tax_amount = 0;
        $tax = $product->tax;
        $tax = [];
        foreach ($product->tax as $row) {
            $tax_amount = ($row->tax->tax_amount_type == 0) ? $row->tax->tax_amount : $row->tax->tax_amount / 100 * $total_price;
            $total_tax_amount += $tax_amount;

            array_push($tax,
                ['tax_name' => $row->tax->tax_name, 'tax_value' => $row->tax->tax_amount, 'tax_amount' => $tax_amount]);
        }


        $total_extra = 0;
        $request_extra = $request->get('extra');
        if (!empty($request_extra)) {
            $extra_qty = $request->get('extra_qty');
            $extra = \App\Models\ExtraItem::whereIn('id_extra', $request->get('extra'))->get();
            if (!$extra) {
                if (app()->getLocale() == 'id') {
                    return json_encode(['status' => 404, 'message' => 'Extra tidak ditemukan']);
                }
                return json_encode(['status' => 404, 'message' => 'Extra not found']);
            }

            foreach ($extra as $row) {
                foreach ($request->get('extra') as $key => $ext) {
                    if ($ext == $row->id_extra) {
                        $total_extra += ($row->extra_price_type == 0) ? $row->amount : ($row->amount * $extra_qty[$key]);
                    }

                }
            }


        }

        $voucher_amount = 0;
        $voucher_code = $request->get('voucher_code');
        if (!empty($voucher_code)) {
            $customer = $request->get('customer');
            $voucher = \App\Models\Voucher::where([
                'voucher_code' => $voucher_code
                , 'id_company' => $this->company
                , 'status' => 1
            ])->withCount([
                'order' => function ($q) use ($customer) {
                    $v = [
                        ['id_company', '=', $this->company],
                        ['status', '<', 5]
                    ];
                    if (!empty($customer)) {
                        array_push($v, ['id_customer', '=', $customer]);
                    }
                    $q->where($v);
                }
            ])->first();

            if (!$voucher) {
                if (app()->getLocale() == 'id') {
                    return json_encode(['status' => 404, 'message' => 'Voucher tidak ditemukan']);
                }
                return json_encode(['status' => 404, 'message' => 'Voucher not found']);
            } else {
                if ($voucher->minimum_amount > $total_price) {
                    if (app()->getLocale() == 'id') {
                        return json_encode([
                            'status' => 400,
                            'message' => 'Minimum transaksi adalah ' . $voucher->currency . ' ' . number_format($voucher->minimum_amount)
                        ]);
                    }
                    return json_encode([
                        'status' => 400,
                        'message' => 'Minimum transaction is ' . $voucher->currency . ' ' . number_format($voucher->minimum_amount)
                    ]);
                } else {
                    if ($voucher->id_product && $voucher->id_product != $product->id_product) {
                        if (app()->getLocale() == 'id') {
                            return json_encode(['status' => 404, 'message' => 'Voucher tidak sesuai']);
                        }
                        return json_encode(['status' => 400, 'message' => 'Voucher not valid']);
                    } else {
                        if ($voucher->id_product_category && $voucher->id_product_category != $product->id_product_category) {
                            if (app()->getLocale() == 'id') {
                                return json_encode(['status' => 404, 'message' => 'Voucher tidak sesuai']);
                            }
                            return json_encode(['status' => 400, 'message' => 'Voucher not valid']);
                        } else {
                            if ($voucher->id_product_type && $voucher->id_product_type != $product->id_product_type) {
                                if (app()->getLocale() == 'id') {
                                    return json_encode(['status' => 404, 'message' => 'Voucher tidak sesuai']);
                                }
                                return json_encode(['status' => 400, 'message' => 'Voucher not valid']);
                            } else {
                                if (($voucher->max_user > 0 && $voucher->order_count >= $voucher->max_use) || (!empty($customer) && $voucher->order_count > 0)) {
                                    if (app()->getLocale() == 'id') {
                                        return json_encode(['status' => 404, 'message' => 'Voucher tidak sesuai']);
                                    }
                                    return json_encode(['status' => 400, 'message' => 'Voucher not valid']);
                                } else {
                                    if ($voucher->voucher_type == 0) {

                                        if ($customer != $voucher->id_customer) {
                                            if (app()->getLocale() == 'id') {
                                                return json_encode([
                                                    'status' => 404, 'message' => 'Voucher tidak sesuai'
                                                ]);
                                            }
                                            json_encode([
                                                'status' => 400, 'message' => 'This voucher require registered customer'
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $voucher_amount = ($voucher->voucher_amount_type == 0) ? $voucher->voucher_amount : ($voucher->voucher_amount / 100) * $total_price;


        }

        $discount = $product->discount_amount_type == 1 ? ($product->discount_amount / 100 * $total_price) : $product->discount_amount;
        $total_product_price = $total_price + $total_tax_amount + $product->fee_amount - $discount;
        $total_amount = $total_product_price + $total_extra - $voucher_amount;

        return json_encode([
            'status' => 200, 'message' => 'OK'
            , 'data' => [
                'schedule' => $request->get('schedule'),
                'adult' => $adult,
                'children' => $children,
                'infant' => $infant,
                'unit_name' => $product->pricing[0]->unit->name,
                'currency' => $product->currency,
                'price_detail' => [
                    'unit_name' => $product->pricing[0]->unit->name,
                    'adult' => $t_adt_price,
                    'children' => $t_chd_price,
                    'infant' => $t_inf_price,
                ],
                'tax_detail' => $tax,
                'total_amount' => $total_amount,
                'total_amount_text' => number_format($total_amount, 0),
                'amount' => $total_price,
                'amount_text' => number_format($total_price, 0),
                'total_product_price' => number_format($total_product_price, 0),
                'total_discount' => $discount,
                'total_tax_amount' => $total_tax_amount,
                'total_tax_amount_text' => number_format($total_tax_amount, 0),
                'total_voucher_amount' => $voucher_amount,
                'total_voucher_amount_text' => number_format($voucher_amount, 0),
                'total_fee' => $product->fee_amount
            ]
        ]);
    }


    /**
     * process function make order
     *
     * @param mixed $send_inv
     *
     * @return void
     */
    public function make_order($send_inv = false)
    {
        $request = app('\App\Http\Requests\CreateOrderFormRequest');
        $this->company = $request->get('my_company') ? $request->get('my_company') : 0;

        $validate_schedule = $this->validate_schedule($this->company);
        $response = json_decode($validate_schedule);
        if ($response->status != 200) {
            return $validate_schedule;
        }

        $pr = $this->get_total_amount($this->company);
        $pricing = json_decode($pr);
        if ($pricing->status != 200) {
            return $pr;
        }

        $id = null;
        \DB::transaction(function () use ($request, $pricing, &$id) {
            $customer = $this->check_customer($request);

            $pricing = $pricing->data;
            $transaction_type = $request->get('transaction_type');


            $product = $request->get('product');

            $product = \App\Models\Product::where(['id_company' => $this->company, 'id_product' => $product])->first();

            $schedule = $request->get('schedule');
            $voucher_code = $request->get('voucher_code');

            if ($product->booking_confirmation == 1) {
                $status = $request->get('status') ? $request->get('status') : 0;
            }

            if ($product->booking_confirmation == 0) {
                $status = $request->get('status') ? $request->get('status') : 8;
            }


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

            $amount = 0;

            $number = microtime(true);
            $number = str_replace('.', '', $number);

            $invoice_no = 'INV' . $this->company . $custno . $product->id_product_type . $number;

            $data_order_header = [
                'invoice_no' => $invoice_no,
                'id_company' => $this->company,
                'transaction_type' => $transaction_type,
                'id_customer' => $customer ? $customer->id_customer : null,
                'id_user_agen' => $transaction_type ? $request->get('agent') : null,
                'product_discount' => $pricing->total_discount,
                'amount' => $pricing->amount,
                'fee' => $pricing->total_fee,
                'total_amount' => $pricing->total_amount,
                'status' => $status,
                'is_void' => false,
                'external_notes' => $external_notes,
                'internal_notes' => $internal_notes,

            ];

            if ($product->booking_confirmation == 1) {
                $data_order_header['allow_payment'] = 1;
            } else {
                $data_order_header['allow_payment'] = 0;
            }

            if (!empty($voucher_code)) {
                $voucher = \App\Models\Voucher::where([
                    'voucher_code' => $voucher_code
                    , 'id_company' => $this->company
                    , 'status' => 1
                ])->first();
                if (!$voucher) {
                    return json_encode(['status' => 404, 'message' => 'Voucher not found']);
                }

                $voucher_has_use = Order::where([
                    ['id_company', '=', $this->company],
                    ['id_customer', '=', $custno],
                    ['id_voucher', '=', $voucher->id_voucher],
                    ['status', '<', 9]
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
                            $used = Order::where([
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
            //print_r($data_order_header);exit;
            $order_header = Order::create($data_order_header);
            $oldPath = 'uploads/products/' . $product->main_image; // publc/images/1.jpg
            if (!\File::exists(public_path($oldPath))) {
                $oldPath = public_path('img/img2.jpg');
            }

            $fileExtension = \File::extension($oldPath);
            $newName = $invoice_no . '-' . $product->unique_code . '.' . $fileExtension;
            $newPathWithName = 'uploads/orders/' . $newName;

            //Copy Main Image
            \File::copy($oldPath, $newPathWithName);

            $rate = 1;

            $data_order_detail = [
                'invoice_no' => $invoice_no,
                'schedule_date' => date('Y-m-d', strtotime($schedule)),
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
                'product_total_price' => $pricing->amount,
                'adult' => $adult,
                'children' => $children ? $children : 0,
                'infant' => $infant ? $infant : 0,
                'unit_name_id' => $product->pricing[0]->unit_name_id,
                'main_image' => $newName,
                'product_total_tax' => $pricing->total_tax_amount,
                'fee_name' => $product->fee_name,
                'fee_amount' => $product->fee_amount,
                'discount_name' => $product->discount_name,
                'discount_amount_type' => $product->discount_amount_type,
                'discount_amount' => $pricing->total_discount,
                'notes' => $external_notes,
                'adult_price' => $pricing->price_detail->adult,
                'children_price' => $pricing->price_detail->children,
                'infant_price' => $pricing->price_detail->infant,

            ];

            //]);
            //}
            $detail = \App\Models\OrderDetail::create($data_order_detail);


            $data_order_tax = [];
            foreach ($product->tax as $row) {
                array_push($data_order_tax, [
                    'order_detail_id' => $detail->id,
                    'id_tax' => $row->id_tax,
                    'tax_name' => $row->tax->tax_name,
                    'tax_amount' => $row->tax->tax_amount ? $row->tax->tax_amount : 0,
                    'tax_amount_type' => $row->tax->tax_amount_type,
                ]);
            }

            \App\Models\OrderTax::insert($data_order_tax);


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
                                    'invoice_no' => $invoice_no,
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
                    \App\Models\OrderExtraItem::insert($data_order_extra);
                }

            }


            //$data_order_customer = [];
            //foreach($request->get('first_name') as $row){
            //array_push($data_order_customer,[
            $data_order_customer = [
                'invoice_no' => $invoice_no,
                'person_type' => 1,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'email' => $email,
                'id_city' => $city,
                'address' => $address,
                'passport' => $passport,
            ];
            //    ]);
            //}
            \App\Models\OrderCustomer::create($data_order_customer);

            //Update Sold Jadi 1
            $product->sold = $product->sold + 1;
            $product->save();

            $id = $invoice_no;

            $agent = \App\Models\UserAgent::where('id_company', $this->company)->get();
            $notif_service = app('\App\Services\NotificationService');
            foreach ($agent as $row) {
                $notif_service->add_company_notif($row->id_user_agen, 100, Route('company.order.edit', $id), $id,
                    'New Order : ' . $id);
            }

        });

        if ($send_inv) {
            $this->allMailCustomer($this->company, $id);
            $this->sendWACustomer($id);
//            $this->send_invoice($this->company, $id);
        }


        //Send Notif to Company
        $this->allMailProvider($this->company, $id);
        $this->sendWAProvider( $id);
        \Log::info('WAProvider from'.ProductService::class.' line 624');
//        $this->send_email_notif($this->company, $id);


        return json_encode([
            'status' => 200,
            'message' => 'New Order has been Created',
            'data' => ['invoice' => $id]
        ]);


    }

    public function check_customer(Request $request)
    {

        $this->company = $request->get('my_company') ? $request->get('my_company') : 0;

        $utility = app('\App\Services\UtilityService');
        $request['phone'] = $utility->format_phone($request->get('phone'));

        $email = $request->get('email');
        $cust = \App\Models\Customer::where(['email' => $email, 'id_company' => $this->company])->first();
        if (!$cust) {
            $cust = $this->auto_register_customer($request);
        }

        return $cust;

    }

    public function auto_register_customer(Request $request)
    {

        $this->company = $request->get('my_company') ? $request->get('my_company') : 0;

        $email = $request->get('email');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');
        $phone = $request->get('phone');

        $city = $request->get('city');
        $address = $request->get('address');

        $customer = \App\Models\Customer::create([
            'id_company' => $this->company,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
        ]);

        $address = \App\Models\CustomerAddress::create([
            'id_customer' => $customer->id_customer,
            'id_city' => $city,
            'address' => $address,
            'is_primary' => true,
        ]);

        return $customer;

    }

    public function add_log(\App\Models\Product $product)
    {
        $product->viewed = $product->viewed + 1;
        $product->save();
    }

    public function allMailCustomer($company, $id)
    {
        $company = \App\Models\Company::find($company);
        $order = Order::find($id);


        switch ($order->payment->payment_gateway) {
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
            case 'Xendit DANA':
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

        if (empty($payment)) {
            return apiResponse('500', 'Error No Code Payment');
        }
        $data = ['company' => $company, 'order' => $order, 'payment' => $payment];

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

        // JIka tidak ada email
        if (empty($to)) {
            return false;
        }

        $from = 'support@' . env('APP_URL');
        $template = null;
        $subject = null;
        $pdf = null;
        //Send Mail INVOICE
        if ($order->status == 0) {
            if (isset($order->customer_manual_transfer) && optional($order->customer_manual_transfer)->status == 'rejected_reupload' && $order->status == 0) {
                $subject = 'Payment for order #' . $id . ' is not verified';
                if ($order->booking_type == 'online') {
                    $template = 'mail.manual-transfer.reupload';
                    $pdf = 'mail.manual-transfer.pdfReupload';
                } else {
                    $template = 'mail.manual-transfer.offline-reupload';
                    $pdf = 'mail.manual-transfer.offline-pdfReupload';
                }
            } else {
                $subject = "Order Invoice & Itinerary for " . $company->company_name;
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.unpaidbooking';
                    $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidBooking';
                } else {
                    $template = 'dashboard.company.order.mail.unpaidbookingoffline';
                    $pdf = 'dashboard.company.order.mail.pdfUnpaidBookingOffline';
                }
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
            // $subject = 'Payment for order #'.$id.' is not verified';
            // if ($order->booking_type == 'online') {
            //     $template = 'mail.manual-transfer.reupload';
            //     $pdf = 'mail.manual-transfer.pdfReupload';
            // } else {
            //     $template = 'mail.manual-transfer.offline-reupload';
            //     $pdf = 'mail.manual-transfer.offline-pdfReupload';
            // }

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
            $template = 'dashboard.company.order.mail_customer.cancelbookingbyprovider';
            $pdf = 'dashboard.company.order.mail_customer.pdfCancelBookingByProvider';
            // if ($order->booking_type == 'online') {
            // }
            // else {
            //     $template = 'dashboard.company.order.mail.cancelbookingofflinebyprovider';
            //     $pdf = 'dashboard.company.order.mail.pdfCancelBookingOfflineByProvider';
            // }
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

        switch ($order->payment->payment_gateway) {
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
            case 'Xendit DANA':
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
        $data = ['company' => $company, 'order' => $order, 'payment' => $payment];
        if (empty($payment)) {
            return apiResponse('500', 'Error No Code Payment');
        }
        $to = $order->company->email_company;
        // JIka tidak ada email
        if (empty($to)) {
            return false;
        }
        $from = 'support@' . env('APP_URL');
        $template = null;
        $subject = null;
        $pdf = null;
        if (!empty($company->email_company) && $order) {
            if ($order->status == 1) {
                if ($order->status == 1 && optional($order->payment)->status == 'PAID') {
                    $subject = "Settlement Complete#" . $id;
                }
                if ($order->status == 1 && optional($order->payment)->status == 'PENDING') {
                    $subject = "New Confirmed Booking #" . $id;
                }
                if ($order->booking_type == 'online') {
                    $template = 'dashboard.company.order.mail_customer.paidprovider';
                    $pdf = 'dashboard.company.order.mail_customer.pdfPaidProvider';
                } else {
                    $template = 'dashboard.company.order.mail.paymentsuccessnotif';
                    $pdf = 'dashboard.company.order.mail.pdfPaymentSuccessnotif';
                }
            } elseif ($order->status == 0) {
                if (isset($order->customer_manual_transfer) && in_array(optional($order->customer_manual_transfer)->status, ['need_confirmed', 'customer_reupload']) && $order->status == 0) {
                    $subject = 'Payment for order #' . $id . ' Awaiting confirmation';
                    $template = 'mail.manual-transfer.customer-upload';
                    $pdf = 'mail.manual-transfer.pdfCustomer-upload';
                } else {
                    $subject = "Unpaid Booking #" . $id;
                    if ($order->booking_type == 'online') {
                        $template = 'dashboard.company.order.mail_customer.unpaidprovider';
                        $pdf = 'dashboard.company.order.mail_customer.pdfUnpaidProvider';
                    } else {
                        $template = 'dashboard.company.order.mail.unpaidprovider';
                        $pdf = 'dashboard.company.order.mail.pdfUnpaidProvider';
                    }
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
            \Mail::send($template, $data, function ($message) use ($order, $data_pdf, $data, $to, $from, $subject) {
                $message->to($to)->subject($subject);
                if (isset($order->customer_manual_transfer) && optional($order->customer_manual_transfer)->status == 'need_confirmed') {
                    $message->attach(storage_path('app/public' . str_replace('storage/', '', $order->customer_manual_transfer->upload_document)));
                }
                $message->attachData($data_pdf->output(), $subject . '.pdf');
                $message->from($from, 'Admin Gomodo');
            });
        }
    }

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

        $to = $order->customer_info->email;
        // JIka tidak ada email
        if (empty($to)) {
            return false;
        }
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

    public function send_email_notif($company, $id)
    {
        $company = \App\Models\Company::find($company);
        $order = \App\Models\Order::find($id);
        $email_view_data = ['company' => $company, 'order' => $order];

        $to = $order->company->email_company;
        // JIka tidak ada email
        if (empty($to)) {
            return false;
        }
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

    public function sendWACustomer($id)
    {
        $order = Order::find($id);
        if ($order):
            if ($order->customer_info->phone) {
                $rule = [
                    'phone' => 'phone:AUTO,ID'
                ];
                if (!\Validator::make(['phone' => $order->customer_info->phone], $rule)->fails()):
                    $message = [];
                    $http = (\request()->isSecure() ? 'https://' : 'http://') . $order->company->domain_memoria;
                    switch ($order->status):
                        case 0:
                            if (isset($order->customer_manual_transfer) && optional($order->customer_manual_transfer)->status == 'rejected_reupload' && $order->status == 0):
                                $url = $http . '/retrieve_booking?no_invoice=' . $order->invoice_no;
                                $message[] = 'Your order with invoice number #' .$order->invoice_no. ' has been canceled by '.$order->company->company_name.' because your payment could not be verified dan Please re-upload your proof of payment. To see your order details, please click: ' . $url .'';
                                $message[] = 'Pesanan Anda dengan nomor faktur #'.$order->invoice_no. ' telah dibatalkan oleh '.$order->company->company_name. ' karena pembayaran Anda tidak dapat diverifikasi dan silahkan upload ulang kembali bukti pembayaran. Untuk melihat detail pesanan Anda, silakan klik: '. $url .'';
                            else:
                                if ($order->booking_type == 'online'):
                                    $message[] = 'Your order ' . $order->order_detail->product_name . ' at ' . $order->company->company_name . ' has been placed successfully. To see your order details, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                    $message[] = 'Anda telah berhasil memesan ' . $order->order_detail->product_name . ' di  ' . $order->company->company_name . '. Untuk melihat detail pesanan Anda, silakan klik: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                else:
                                    $message[] = 'Dear Customer, you have received e-invoice for your order from ' . $order->company->company_name . '. To see your order details, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                    $message[] = 'Pelanggan Yth, Anda telah menerima e-invoice untuk pesanan Anda di ' . $order->company->company_name . '' . '. Untuk melihat detail pesanan Anda, silakan klik: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                endif;
                            endif;
                            break;
                        case 1:
                            if ($order->booking_type == 'online'):
                                $message[] = 'Congratulations, your payment for ' . $order->order_detail->product_name . ' has been successfully made. To see your order details, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                $message[] = 'Selamat, pembayaran Anda untuk ' . $order->order_detail->product_name . ' telah berhasil dilakukan. Untuk melihat detail pesanan Anda, silakan klik: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                            else:
                                $message[] = 'Thank you, your payment for ' . $order->order_detail->invoice_no . ' has been received. To check your order updates, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                                $message[] = 'Terima kasih, pembayaran Anda untuk ' . $order->order_detail->invoice_no . ' telah diterima. Untuk melihat pembaruan pesanan, silakan klik: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                            endif;
                            break;
                        case 5:
                            break;
                        case 6;
                            $message[] = 'Dear Customer, your order ' . $order->order_detail->product_name . ' from ' . $order->company->company_name . ' has been canceled. If you still want to continue the order, please contact us through ' . $order->company->phone_company ?? $order->company->email_company . '. To see your order details, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                            $message[] = 'Pelanggan Yth, pesanan ' . $order->order_detail->product_name . ' Anda dari ' . $order->company->company_name . ' telah dibatalkan. Jika Anda masih ingin melanjutkan pesanan, silakan hubungi kami melalui ' . $order->company->phone_company ?? $order->company->email_company . '. Untuk melihat detail pesanan Anda, silakan klik: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                            break;
                        case 7;
                            $message[] = 'Dear Customer, your order with invoice number ' . $order->invoice_no . ' has expired due to unsuccessful payment. To see your order details, please click: ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                            $message[] = 'Pelanggan Yth, pesanan Anda dengan nomor invoice ' . $order->invoice_no . ' telah kedaluwarsa karena pembayaran gagal dilakukan. Untuk meninjau pesanan Anda, klik  ' . $http . '/retrieve_booking?no_invoice=' . $order->invoice_no . '';
                    endswitch;
                    if (count($message) > 0):
                        return Woowa::SendMessageAsync()->setPhone($order->customer_info->phone)->setMessage(sprintf('%s', implode('\n------------------------------------------\n', $message)))->prepareContent()->send()->getResponse();
                    endif;
                    return [];
                endif;
                return [];
            }
            return [];
        endif;
    }

    public function sendWAProvider($id)
    {
        $order = Order::find($id);
        if ($order):
            if ($order->company->phone_company) {
                $rule = [
                    'phone' => 'phone:AUTO,ID'
                ];
                if (!\Validator::make(['phone' => $order->company->phone_company], $rule)->fails()):
                    $message = [];
                    $http = (\request()->isSecure() ? 'https://' : 'http://').env('APP_URL');
                    
                    switch ($order->status):
                        case 0:
                            if (isset($order->customer_manual_transfer) && in_array(optional($order->customer_manual_transfer)->status, ['need_confirmed', 'customer_reupload']) && $order->status == 0) :
                                $url = $order->booking_type == 'online' ? $http.'/company/order/' . $order->invoice_no . '/edit' : $http.'/company/manual-order/view/' . $order->invoice_no;
                                $message[] = 'Gomodo: Your Customer has submitted a proof of payment. To see the details,  please click: ' . $url . '';
                                $message[] = 'Gomodo: Customer Anda telah mengirimkan bukti pembayaran. Untuk melihat detailnya, silakan klik: ' . $url . '';
                            else :
                                if ($order->booking_type == 'online'):
                                    $message[] = 'Gomodo: You’ve received a new order. To see the details,  please click: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                    $message[] = 'Gomodo: Anda telah menerima pesanan baru. Untuk melihat detailnya, silakan klik: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                else:
                                    $message[] = 'Gomodo: New e-invoice ' . $order->invoice_no . ' has been sent to ' . $order->customer_info->email . ' as a payment bill for ' . $order->order_detail->product_name . '. To see invoice details, please click: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                    $message[] = 'Gomodo: E-invoice baru ' . $order->invoice_no . ' telah dikirim ke ' . $order->customer_info->email . ' sebagai tagihan untuk pembayaran pesanan ' . $order->order_detail->product_name . '. Untuk melihat detail pesanan, silakan klik: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                endif;

                            endif;
                            break;
                        case 1:
                            if ($order->payment) {
                                if (strtoupper($order->payment->status) == 'PAID') {
                                    if ($order->booking_type == 'online'):
                                        $message[] = 'Gomodo: Congratulations, payment for ' . $order->order_detail->product_name . ' with invoice number: ' . $order->invoice_no . ' has been received and added to your balance. To see the details,  please click: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                        $message[] = 'Gomodo: Selamat, pembayaran untuk ' . $order->order_detail->product_name . ' dengan nomor faktur: ' . $order->invoice_no . ' telah diterima dan ditambahkan ke saldo Anda. Untuk melihat detailnya, silakan klik: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                    else:
                                        $message[] = 'Gomodo: Congratulations, payment for ' . $order->order_detail->product_name . ' with invoice number: ' . $order->invoice_no . ' has been received and added to your balance. To see your order details, please click: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                        $message[] = 'Gomodo: Selamat, pembayaran untuk ' . $order->order_detail->product_name . ' dengan nomor faktur: ' . $order->invoice_no . ' telah diterima dan ditambahkan ke saldo Anda. Untuk melihat detail pesanan Anda, silakan klik: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                    endif;
                                } elseif (strtoupper($order->payment->status) == 'PENDING') {
                                    if ($order->booking_type == 'online'):
                                        $message[] = 'Gomodo: Order with invoice number ' . $order->invoice_no . ' has been paid by the customer. To see the details,  please click: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                        $message[] = 'Gomodo: Pesanan dengan nomor invoice ' . $order->invoice_no . ' telah dibayar oleh pelanggan Anda. Untuk melihat detailnya, silakan klik: ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                    else:
                                        $message[] = 'Gomodo: E-invoice ' . $order->invoice_no . ' has been paid by your customer . To see your order details, please click: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                        $message[] = 'Gomodo: E-invoice ' . $order->invoice_no . ' telah dibayar oleh pelanggan Anda. Untuk melihat detail pesanan Anda, silakan klik: ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                    endif;
                                }
                            }

                            break;
                        case 5:
                        case 6;
                            break;
                        case 7;
                            if ($order->booking_type == 'online'):
                                $message[] = 'Order with invoice number '.$order->invoice_no.' has expired because the customer failed to make payments. To review the order, go to : ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                                $message[] = 'Pesanan dengan nomor invoice '.$order->invoice_no.' kedaluwarsa karena pelanggan gagal melakukan pembayaran. Untuk meninjau pesanan , klik  ' . $http.'/company/order/' . $order->invoice_no . '/edit';
                            else:
                                $message[] = 'Order with invoice number '.$order->invoice_no.' has expired because the customer failed to make payments. To review the order, go to : ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                                $message[] = 'Pesanan dengan nomor invoice '.$order->invoice_no.' kedaluwarsa karena pelanggan gagal melakukan pembayaran. Untuk meninjau pesanan , klik  ' . $http.'/company/manual-order/view/' . $order->invoice_no;
                            endif;
                    endswitch;
                    if (count($message) > 0):
                        return Woowa::SendMessageAsync()->setPhone($order->company->phone_company)->setMessage(sprintf('%s', implode('\n--------------------------\n', $message)))->prepareContent()->send()->getResponse();
                    endif;
                    return [];
                endif;
                return [];
            }
            return [];
        endif;
    }

}
