<?php


//    Route::get('test/truncate', function () {
//        DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
//        DB::table('tbl_order_header')->truncate();
//        DB::table('tbl_order_detail')->truncate();
//        DB::table('tbl_order_customer')->truncate();
//        DB::table('tbl_order_extra')->truncate();
//        DB::table('tbl_order_tax')->truncate();
//        DB::table('tbl_payment')->truncate();
//        DB::table('tbl_journal_gxp')->truncate();
//        DB::table('tbl_voucher_cashback')->truncate();
//        DB::table('tbl_company_withdraw_request')->truncate();
//        DB::statement("SET FOREIGN_KEY_CHECKS = 1;");
//    });

use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Update;
use App\Scopes\ActiveProviderScope;
use Carbon\Carbon;
use Illuminate\Support\Str;

Route::get('test/fix-account', function () {
    \App\Models\UserAgent::query()->update(['status' => 1]);
    \App\Models\Company::query()->update(['status' => 1]);
});

Route::get('test/session', function () {
    dd(Session::all());
});
Route::get('test/domain-fix', function () {
    $OldDomain = '.' . env('B2B_DOMAIN');
    $domains = \App\Models\Company::where('domain_memoria', 'like', '%' . $OldDomain)->get();

    foreach ($domains as $domain) {
        $domain->update([
            'domain_memoria' => str_replace($OldDomain, '.' . env('APP_URL'), $domain->domain_memoria)
        ]);
    }
});

Route::get('test-kayiz', 'Test\TestCtrl@getExchange');

Route::get('fix-voucher', function () {
    $vouchers = \App\Models\Voucher::all();
    foreach ($vouchers as $voucher) {
        $voucher->update([
            'start_date' => \Carbon\Carbon::parse($voucher->created_at)->addMonth(1)->toDateString(),
            'end_date' => \Carbon\Carbon::parse($voucher->created_at)->addMonth(1)->toDateString(),
            'valid_start_date' => \Carbon\Carbon::parse($voucher->created_at)->addMonth(1)->toDateString(),
            'valid_end_date' => \Carbon\Carbon::parse($voucher->created_at)->addMonth(1)->toDateString()
        ]);
    }
});

Route::get('voucher', function () {
    $max = 3;
    $vouchers = \App\Models\Voucher::where('by_gomodo', 1)
        ->where('status', 1)
        ->get();
    foreach ($vouchers as $voucher) {
        $now = \Carbon\Carbon::now();
        $diff = \Carbon\Carbon::now()->diffInMonths($voucher->created_at);
        if ((\Carbon\Carbon::now()->toDateTimeString() > $voucher->created_at) && ($diff < $max)) {
            echo `Now : ` . $now->toDateString() . `  ` . `  Valid : ` . $voucher->valid_end_date;


            if (\Carbon\Carbon::now()->toDateString() > $voucher->valid_end_date) {
                $voucher->update([
                    'start_date' => \Carbon\Carbon::parse($voucher->start_date)->addMonth(1)->toDateString(),
                    'end_date' => \Carbon\Carbon::parse($voucher->end_date)->addMonth(1)->toDateString(),
                    'valid_start_date' => \Carbon\Carbon::parse($voucher->valid_start_date)->addMonth(1)->toDateString(),
                    'valid_end_date' => \Carbon\Carbon::parse($voucher->valid_end_date)->addMonth(1)->toDateString()
                ]);
            } else {
                echo "Masih Berlaku";
            }

        } else {
            echo "Tidak berlaku";
        }
    }
});

Route::get('test-grafix', function () {
    $totalProvider = [];
    $totalOrder = [];
    $totalWithdrawals = [];
    $totalGMV = [];
    $totalProductCreated = [];

    $start = \Carbon\Carbon::parse('2019-01-01 00:00:00');
    $end = \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->diffInMonths($start);
    $months = [
        'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'
    ];
    for ($i = 0; $i <= $end; $i++) {
        $start = \Carbon\Carbon::parse('2019-01-01 00:00:00');
        $destination = $start->addMonth($i)->format('Y-m');
        $ex = explode('-', $destination);
        $totalOrder[] = [
            'bulan' => $months[(int)$ex[1] - 1] . ' ' . $ex[0],
            'total' =>
                \App\Models\Order::where('status', 1)->whereHas('payment', function ($payment) {
                    $payment->where('payment_gateway', '!=', 'Cash On Delivery');
                })->where('created_at', 'like', $destination . '%')->count()
        ];
        $totalGMV[] = [
            'bulan' => $months[(int)$ex[1] - 1] . ' ' . $ex[0],
            'total' =>
                \App\Models\Order::where('status', 1)->whereHas('payment', function ($payment) {
                    $payment->where('payment_gateway', '!=', 'Cash On Delivery');
                })->where('created_at', 'like', $destination . '%')->sum('total_amount')
        ];
        $totalProductCreated[] = [
            'bulan' => $months[(int)$ex[1] - 1] . ' ' . $ex[0],
            'total' =>
                \App\Models\Product::where('booking_type', 'online')->where('created_at', 'like',
                    $destination . '%')->count()
        ];
        $totalProvider[] = [
            'bulan' => $months[(int)$ex[1] - 1] . ' ' . $ex[0],
            'total' =>
                \App\Models\Company::where('created_at', 'like', $destination . '%')->count()
        ];

        $totalWithdrawals[] = [
            'bulan' => $months[(int)$ex[1] - 1] . ' ' . $ex[0],
            'total' =>
                \App\Models\WithdrawRequest::where('status', 1)->where('created_at', 'like', $destination . '%')->count()
        ];
    }
    $data['totalOrder'] = $totalOrder;
    $data['totalProductCreated'] = $totalProductCreated;
    $data['totalProvider'] = $totalProvider;
    $data['totalWithdrawals'] = $totalWithdrawals;
    skema($data);
});

Route::get('test-email', 'Test\TestCtrl@getExchange');
Route::get('test-email-2', function () {
//    $response = Odenktools\Bca\Facades\Bca::httpAuth();
//    $response = json_encode($response);
//    $access_token = json_decode($response)->body->access_token;
//    $rateType = 'erate';
//
//    $mataUang = '';
//
//    $response = Odenktools\Bca\Facades\Bca::getForexRate($access_token, $rateType, $mataUang);
//    dd( json_decode(json_encode($response)));
    $dom = new DOMDocument();
    $regex = '/(\s|\\\\[rntv]{1})/';
    $rawcontents = file_get_contents('http://www.bi.go.id/id/moneter/informasi-kurs/transaksi-bi/');
    $data_table = explode('<div id="right-cell">', $rawcontents);
    $data_table = explode('<table class="table1" cellspacing="0" rules="all" border="1" id="ctl00_PlaceHolderMain_biWebKursTransaksiBI_GridView1">',
        $data_table[1]);
    $data_table = explode('</table>', $data_table[1]);
    $html = $data_table[0];
    @$dom->loadHTML($html);
    $kurs = [];
    $rows = $dom->getElementsByTagName('tr');
    $count = 0;

    $baca = ['currency', 'dollar_value', 'kurs_beli', 'kurs_jual'];
    foreach ($rows as $row) {
        if ($count > 0) {
            if (!empty($row->nodeValue)) {
                $n = 0;
                $child = [];
                foreach ($row->childNodes as $colum) {
                    if (!empty(preg_replace($regex, '', $colum->nodeValue))) {
                        $name = $baca[$n++];
                        $child[$name] = preg_replace($regex, '', $colum->nodeValue);
                    }
                }
                $kurs[$count - 1] = $child;
            }
        }
        $count++;

    }
    $kurs = collect($kurs);
    echo "BI Exchange Rate\n";
    skema(toObject(collect($kurs->firstWhere('currency', 'USD'))->forget('dollar_value')->toArray()));
//    skema(toObject(collect($kurs->firstWhere('currency','USD'))->forget('dollat_value')));

    $rawcontents = file_get_contents('http://bca.co.id');
    $data_table = explode('<div class="table-responsive col-md-8 kurs-e-rate">', $rawcontents);
    $data_table = explode('<div class="kurs-converter col-md-4">', $data_table[1]);
    $html = $data_table[0];
    @$dom->loadHTML($html);
    $rows = $dom->getElementsByTagName('tr');
    $kurs = [];
    $count = 0;
    $baca = ['currency', 'kurs_beli', 'kurs_jual'];
    foreach ($rows as $row) {
        if ($count > 0) {
            if (!empty($row->nodeValue)) {
                $n = 0;
                $child = [];
                foreach ($row->childNodes as $colum) {
                    if (!empty(preg_replace($regex, '', $colum->nodeValue))) {
                        $name = $baca[$n++];
                        $child[$name] = preg_replace($regex, '', $colum->nodeValue);
                    }
                }
                $kurs[$count - 1] = $child;
            }
        }
        $count++;
    }
    $kurs = collect($kurs);
    echo "BCA Exchange Rate\n";
    skema(toObject($kurs->firstWhere('currency', 'USD')));

    $rawcontents = file_get_contents('https://www.bni.co.id/id-id/beranda/informasivalas');
//    dd($rawcontents);
    $data_table = explode('<div class="content-infoView">', $rawcontents);
//    dd($data_table[1]);
    $data_table = explode('<div class="date-infoView">', $data_table[1]);
    $html = $data_table[0];
    @$dom->loadHTML($html);
    $rows = $dom->getElementsByTagName('tr');
    $kurs = [];
    $count = 0;
    $baca = ['currency', 'kurs_beli', 'kurs_jual'];
    foreach ($rows as $row) {
        if ($count > 0) {
            if (!empty($row->nodeValue)) {
                $n = 0;
                $child = [];
                foreach ($row->childNodes as $colum) {
                    if (!empty(preg_replace($regex, '', $colum->nodeValue))) {
                        $name = $baca[$n++];
                        $child[$name] = preg_replace($regex, '', $colum->nodeValue);
                    }
                }
                $kurs[$count - 1] = $child;
            }
        }
        $count++;
    }
    $kurs = collect($kurs);
//    dd($kurs);
    echo "BNI Exchange Rate\n";
    skema(toObject($kurs->firstWhere('currency', 'USD')));
});


// Midtrans Testing
Route::post('/midtrans-post', function (\Illuminate\Http\Request $request) {
    $data = $request->all();

    return response()->json(Gomodo\Midtrans\MidTrans::notification($data)->getNotification());
});

Route::get('/midtrans', function () {
    return view('midtrans.test');
});

Route::get('/test-midtrans', function () {
    $array = [
        'transaction_details' => [
            'order_id' => str_random(14),
            'gross_amount' => 10000
        ],
        "item_details" => [
            "id" => "ITEM1",
            "price" => 10000,
            "quantity" => 1,
            "name" => "Midtrans Bear",
            "brand" => "Midtrans",
            "category" => "Toys",
            "merchant_name" => "Midtrans"
        ],
        "customer_details" => [
            "first_name" => "TEST",
            "last_name" => "MIDTRANSER",
            "email" => "test@midtrans.com",
            "phone" => "+628123456"
        ],
        "enabled_payments" => ["credit_card", "mandiri_clickpay", "cimb_clicks",
            "bca_klikbca", "bca_klikpay", "bri_epay", "echannel", "permata_va",
            "bca_va", "bni_va", "other_va", "gopay", "indomaret", "alfamart",
            "danamon_online", "akulaku"],

    ];

    if ($array['enabled_payments'][0] == 'gopay') {
        $array["gopay"] = [
            "enable_callback" => true,
            "callback_url" => "http://gopay.com"
        ];
    }

    $a = Gomodo\Midtrans\MidTrans::pay($array)->send();
    return response()->json($a->data->token);
});

Route::get('test-date', function () {
    $a = Carbon::now()->addDays(5)->format('N');
    if ($a === "6") {
        $time = Carbon::now()->addDays(7)->toDateTimeString();
    } elseif ($a === "7") {
        $time = Carbon::now()->addDays(6)->toDateTimeString();
    } else {
        $time = Carbon::now()->addDays(5)->toDateTimeString();
    }
    dd($time);
});

Route::get('file', function () {
    return view('test.file');
});

Route::get('crop', function () {
    return view('test.crop');
});

Route::post('file', function (\Illuminate\Http\Request $request) {
    $file = $request->input('image');
    if (!File::isDirectory(storage_path('app/public/uploads/test'))):
        File::makeDirectory(storage_path('app/public/uploads/test'), 0777, true, true);
    endif;
    $folder = storage_path('app/public/uploads/test');
    $filename = 'test-file.jpeg';
    Image::make($file)->save($folder . '/' . $filename);
    return redirect()->back();
});

Route::post('crop', function (\Illuminate\Http\Request $request) {
    $file = $request->file('image');
    $left = (int)$request->input('left');
    $top = (int)$request->input('top');
    $width = (int)$request->input('width');
    $height = (int)$request->input('height');
    if (!File::isDirectory(storage_path('app/public/uploads/test'))):
        File::makeDirectory(storage_path('app/public/uploads/test'), 0777, true, true);
    endif;
    $folder = storage_path('app/public/uploads/test');
    $filename = 'test-file-crop.jpeg';
    Image::make($file)->crop($width, $height, $left, $top)->save($folder . '/' . $filename);
    return redirect()->back();
});
Route::get('php', function () {
    phpinfo();
});

Route::get('/test-dana', function () {
    $order = \App\Models\Order::latest()->first();
    $dataDana = [
        'external_id' => $order->invoice_no,
        'amount' => 10000,
        'callback_url' => route('ewallet.callback'),
        'redirect_url' => route('invoice.dana', ['invoice', $order->invoice_no]),
        'ewallet_type' => 'DANA'
    ];
    $response = \Gomodo\Xendit\Xendit::CreateEWallet($dataDana)->send();
    $order->payment->update(['response' => $response]);
//    dd($response);
});

Route::get('/test-qrcode', function(){
    $data = [
        'external_id' => str_random(14),
        'type' => 'DYNAMIC',
        'callback_url' => 'http://gomodo.id',
        'amount' => 1500
    ];
    $id = 'q85RxxO3WvTLTy';
    $res = \Gomodo\Xendit\Xendit::CreateQrCodesSimulate($id)->send();
    dd($res);
});

Route::get('b', function () {
    $ext = 'https://www.google.com';
    $order = Order::latest()->first();
    $http = env('HTTPS', false) == true ? 'https://' : 'http://';
    $newCompany = $order->company;
    // http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}
    return redirect()->to($http.$newCompany->domain_memoria.'/invoice/akulaku/'.$order->invoice_no)->send();
    // return redirect()->route('invoice.akulaku', ['no_invoice' => $res->order_id]);
});

Route::get('/test-linkaja', function () {
    $dataLink = [
        "external_id" => str_random(14),
        "phone" => "089911111111",
        "amount" => 300000,
        "items" => [
            [
                "id" => "123123",
                "name" => "Phone Case",
                "price" => 100000,
                "quantity" => 1

            ],
            [
                "id" => "345678",
                "name" => "Powerbank",
                "price" => 200000,
                "quantity" => 1
            ]
        ],
        "callback_url" => "https://yourwebsite.com/callback",
        "redirect_url" => "https://yourwebsite.com/order/123",
        "ewallet_type" => "LINKAJA",
    ];

    $res = \Gomodo\Xendit\Xendit::CreateEWallet($dataLink)->send();
    dd($res);
});


Route::get('/test-ovo', function () {
    $order = \App\Models\Order::latest()->first();
    $dataOVO = [
        'external_id' => str_random(14),
        'amount' => 4444,
        'phone' => $order->customer->phone,
        'ewallet_type' => 'OVO'
    ];

    $res = \Gomodo\Xendit\Xendit::CreateEWallet($dataOVO)->send();
    dd($res);
});

Route::get('/listpayment', function () {
    $company = \App\Models\Company::find(52);
    $list = \App\Models\ListPayment::whereIn('code_payment', ['credit_card', 'dana', 'linkaja'])->get();
    $list->each(function ($item, $key) use ($company) {
        $company->payments()->updateExistingPivot($item->id, ['charge_to' => 1]);
    });
});

Route::get('ux_role', function () {
    if (!\App\Models\Role::whereRoleSlug('ux')->first()):
        $ux = new \App\Models\Role();
        $ux->role_name = "UX";
        $ux->role_slug = "ux";
        $ux->save();
        $newUx = new \App\Models\Admin();
        $newUx->admin_name = "UX";
        $newUx->role_id = $ux->id;
        $newUx->email = "ux@gomodo.tech";
        $newUx->password = bcrypt("password");
        $newUx->save();

    endif;
});

Route::get('/test-email', function () {
//   $prem = \App\Models\Ads::latest()->first();
    $company = Company::find(55);
    $prem = \App\Models\Kyc::where('id_company', $company->id_company)->first();
    $data = ['company' => $company, 'prem' => $prem];
    $to = 'regasyahfika17@gmail.com';
    // JIka tidak ada email
    if (empty($to)) {
        return false;
    }
    $template = null;
    $subject = 'test email';
    $pdf = null;
//    $image = $prem->document_ads;
    $image = [
        'identity_card' => $prem->identity_card,
        'family_card' => $prem->family_card,
        'tax_number' => $prem->tax_number,
        'police_certificate' => $prem->police_certificate,
        'bank_statement' => $prem->bank_statement,
        'photo' => $prem->photo,
    ];
//    $loc = storage_path('app/public/image-ads/' . str_replace('storage/', '', $image));
//    foreach ($image as $item) {
//        $loc = storage_path('app/public' . str_replace('storage/', '', $item));
//    }
    $template = view('mail.test-email', $data)->render();

    dispatch(new \App\Jobs\SendEmail($subject, $to, $template, $image, $pdf, $data));
});

Route::get('/test-image', function () {
    $kyc = \App\Models\Kyc::where('id_company', 55)->first();
    $image = [
        'ktp' => $kyc->identity_card,
        'npwp' => $kyc->tax_number
    ];

    $fin = \App\Models\Finance::latest()->first();
    $data = [];
    $company = Company::find(55);
    foreach ($image as $key => $item) {
        $path = 'uploads/kyc/' . $company->id_company;
        $source = storage_path('app/public' . str_replace('storage/', '', $item));
        $getClient = pathinfo(storage_path('app/public' . str_replace('storage/', '', $item)), PATHINFO_EXTENSION);
        if (!File::isDirectory(Storage::disk('public')->path($path))) {
            File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
        }
        $name = 'finance - [' . now() . '] - ' . generateRandomString(6) . time() . '.' . $getClient;
        if (copy($source, storage_path('app/public/uploads/finance/' . $name))) {
            $data[$key] = Storage::url($path . '/' . $name);
        }
    }
    $fin->verification->update($data);

});

Route::get('/export-klhk-product', function () {
    return (new \App\Exports\ProductKLHKExport())
        ->download('Product KLHK.xls');
});
Route::get('/export-all-product', function () {
    return (new \App\Exports\AllProductExport())
        ->download('Product All '.Carbon::now()->format('d M Y H:i:s').'.xls');
});

Route::get('export/provider', function () {
    return (new \App\Exports\Custom\BDReportProvider())
        ->download(time().'-report-all-provider.xls');
});

Route::get('week', function (){
    $targetProvidersThisYear = env('TARGET_PROVIDER',4000);
    $targetRGMVThisYear = env('TARGET_GMV',400000000000);
    $targetProductThisYear = env('TARGET_PRODUCT',12000);
    $deadline = Carbon::now()->endOfYear();
    $today = Carbon::now();
    $remaining = $today->diffInMonths($deadline);
//    dd($remaining);
    $totalProvider = \App\Models\Company::count();
    $data = [];
    $data['remaining'] = $remaining;
    $data['targetProvider'] = $targetProvidersThisYear;
    $data['currentProvider'] = $totalProvider;
    $data['archievedProvider'] = number_format((($totalProvider/$targetProvidersThisYear) * 100),2)." %";
    $data['averagePerMonthProvider'] = (int)round(($targetProvidersThisYear - $totalProvider)/$remaining);
    $totalProduct = \App\Models\Product::count();
    $data['targetProduct'] = $targetProductThisYear;
    $data['currentProduct'] = $totalProduct;
    $data['archievedProduct'] = number_format((($totalProduct/$targetProductThisYear) * 100),2)." %";
    $data['averagePerMonthProduct'] = (int)round(($targetProductThisYear - $totalProduct)/$remaining);

    $orderOnline = \App\Models\Order::where('booking_type', 'online')->where('status', 1)->where('created_at', '>', '2019-03-01 00:00:00')->whereHas('payment');
    $totalVolumeTransaction = $orderOnline->sum('total_amount');
    $orderOffline = \App\Models\Order::where('booking_type', 'offline')->where('status', 1)->where('created_at', '>', '2019-03-01 00:00:00');
    $totalVolumeOffline = $orderOffline->sum('total_amount');

    $data['targetGMV'] = $targetRGMVThisYear;
    $data['currentGMV'] = format_priceID($totalVolumeOffline+$totalVolumeTransaction);
    $data['archievedGMV'] = number_format(((($totalVolumeOffline+$totalVolumeTransaction)/$targetRGMVThisYear) * 100),2)." %";
    $data['averagePerMonthGMV'] = (int)round(($targetRGMVThisYear - ($totalVolumeOffline+$totalVolumeTransaction))/$remaining);
    dd($data);

});

Route::get('tig', function (\App\Services\TigService $tigService){
//    skema($tigService->sendMessage(6285712299001,"Selanjutnya masukan ini 1122"));
//    skema($tigService->sendMessage(6282241214466,"Selanjutnya masukan ini 1122"));
    skema($tigService->pullReport());
});