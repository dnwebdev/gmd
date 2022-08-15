<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kayiz\Woowa;

//use File;
//use Image;
//use Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('countries', 'Api\Region\RegionCtrl@getCountries');
Route::get('states', 'Api\Region\RegionCtrl@getStateFromCountry');
Route::get('cities', 'Api\Region\RegionCtrl@getCityFromState');
Route::get('regional', 'Api\Region\RegionCtrl@getDataRegionalFromCityId');
Route::get('search-city', 'Api\Region\RegionCtrl@searchCity');
Route::group(['middleware' => 'json'], function () {
    Route::post('kirim', 'Api\Auth\RegisterController@kirim');
    Route::post('register', 'Api\Auth\RegisterController@OtpRegister');

    Route::post('resend-register', 'Api\Auth\RegisterController@resendOTP');

    Route::get('testOTP', 'Api\Auth\RegisterController@otpTesting');
//    Route::post('otp/{shortcode}','Company\Register\RegisterCtrl@activateOtp')->name('api:otp-register');
//    Route::post('password/request/otp','Company\Password\RequestPasswordController@requestOTP');
//    Route::post('password/request/otp/{shortcode}','Company\Password\RequestPasswordController@resetPasswordOTP')->name('api:otp-forgot-password');

    Route::post('login', 'Api\Auth\LoginController@loginDefault');
    Route::post('login/phone', 'Api\Auth\LoginController@requestOTP');
    Route::post('login/phone/validate', 'Api\Auth\LoginController@validateOTP');
    Route::middleware('auth:api')->group(function (){
        Route::get('products','Api\Product\ProductController@listProduct');
        Route::get('product/{sku}','Api\Product\ProductController@detailProduct');
        Route::get('check-schedule','Api\Schedule\ValidateController@validateSchedule');
        Route::get('dashboard','Api\Dashboard\DashboardController@data');
        Route::get('orders','Api\Order\OrderController@orderList');
        Route::get('order/{id}','Api\Order\OrderController@orderDetail');
        Route::post('order','Api\Order\OrderController@pay');
        Route::post('approve-manual/{id}','Api\Order\OrderController@manualApprove');
        Route::post('approve-customer/{id}','Api\Order\OrderController@approveCustomer');
    });

    Route::middleware('gta_auth')->prefix('gta')->group(function () {
        Route::prefix('ota')->group(function () {
            Route::get('/', 'Api\Ota\OtaController@index');
            Route::post('/', 'Api\Ota\OtaController@store');
            Route::get('/{id}', 'Api\Ota\OtaController@show');
            Route::post('/{id}', 'Api\Ota\OtaController@update');
            Route::post('/{id}/toggle', 'Api\Ota\OtaController@toggle');
        });

        Route::prefix('product')->group(function () {
            Route::get('/', 'Api\Ota\ProductController@index');
        });
            
    });
});
Route::group(['middleware' => 'web'], function () {

});


Route::post('midtrans-notification', 'Customer\MidtransCtrl@notificationMidtrans')->name('midtrans.notification');
//Route::post('midtrans-pay', 'Customer\MidtransCtrl@payMidtrans')->name('midtrans.pay');
//Route::post('midtrans-post', 'Customer\MidtransCtrl@postMidtrans')->name('midtrans.post');

Route::get('user', function (Request $request) {
    return apiResponse(200, 'OK', $request->user('api'));
})->middleware('auth:api');

Route::group(['middleware' => 'auth:api_client', 'namespace' => 'Client\Api', 'prefix' => 'client'], function () {
    Route::post('create-order', 'Order\OfflineController@create');
});

//Route::post('insurance', function (Request $request) {
//    $product = \App\Models\Product::find(1645);
//    if ($request->has('use_insurance') && count($request->get('use_insurance')) == '1') {
//        $insurance = \App\Models\Insurance::where('id',
//            array_keys($request->get('use_insurance'))[0])->active()->first();
//        if ($insurance && $product->insurances()->where('id', $insurance->id)->first()) {
//            if ($product->duration_type == '0') {
//                $duration = ceil($product->duration / 24);
//            } else {
//                $duration = $product->duration;
//            }
//
//            $totalInsurancePrice = 0;
//            if ($insurance->active_pricings->count() > 0):
//                $insurancePrice = $insurance->active_pricings()->where('day', '>=', $duration)->first()->price;
//                if (!$insurancePrice) {
//                    $insurancePrice = $insurancePrice->active_pricings()->orderBy('day', 'desc')->first()->price;
//                }
//                $totalInsurancePrice = (int)$request->get('pax') * $insurancePrice;
//            endif;
//            $additionalOrder = [
//                'invoice_no' => 'INV' . generateRandomString(10),
//                'quantity' => $request->get('pax'),
//                'price' => $insurancePrice,
//                'total' => (int)$request->get('pax') * $insurancePrice,
//                'description_id' => 'Pembayaran Asuransi ' . $insurance->insurance_name_id,
//                'description_en' => $insurance->insurance_name_en . ' insurance',
//                'type' => 'insurance'
//            ];
//
//            $insuranceDetails = [];
//            foreach ($request->get('insurances') as $insuranceForm):
//                if (isset($insuranceForm['customer'])):
//                    foreach ($insuranceForm['customer'] as $item => $value):
//                        if ($modelInsuranceForm = \App\Models\InsuranceForm::where('insurance_id',
//                            $insurance->id)->where('name', $item)->first()):
//                            $insuranceDetails[] = [
//                                'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
//                                'order_detail_id' => 1,
//                                'insurance_form_id' => $modelInsuranceForm->id,
//                                'label_id' => $modelInsuranceForm->label_id,
//                                'label_en' => $modelInsuranceForm->label_en,
//                                'value' => $value,
//                                'purpose' => 'customer',
//                                'type' => $modelInsuranceForm->type
//                            ];
//                        endif;
//                    endforeach;
//                endif;
//                if (isset($insuranceForm['participants'])):
//                    foreach ($insuranceForm['participants'] as $item => $field):
//                        foreach ($field as $item2 => $value):
//                            if ($modelInsuranceForm = \App\Models\InsuranceForm::where('insurance_id',
//                                $insurance->id)->where('name', $item2)->first()):
//                                $insuranceDetails[] = [
//                                    'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
//                                    'order_detail_id' => 1,
//                                    'purpose_order' => $item,
//                                    'insurance_form_id' => $modelInsuranceForm->id,
//                                    'label_id' => $modelInsuranceForm->label_id,
//                                    'label_en' => $modelInsuranceForm->label_en,
//                                    'value' => $value,
//                                    'purpose' => 'participants',
//                                    'type' => $modelInsuranceForm->type
//                                ];
//                            endif;
//                        endforeach;
//                    endforeach;
//                endif;
//            endforeach;
//
//        }
//    }
//    $result = [
//        'additional_order' => $additionalOrder,
//        'detail_info' => $insuranceDetails
//    ];
//    return apiResponse(200, 'ok', $result);
//});

//Route::post('users/coba', function (Request $request) {
//    dd($request->all());
//});


//Route::post('/test/finance', function (\Illuminate\Http\Request $request) {
//    if ($request->get('amount') < 10000000 || $request->get('amount') > 2000000000) {
//        return [
//            'message' => 'kebanyakan atau kelebihan'
//        ];
//    }
//
//    try {
//        \DB::beginTransaction();
//        $company = \App\Models\Company::find(55);
//        $time = \App\Models\TimeFinance::find(1);
//        $type = \App\Models\TypeFinance::find(1);
//        $kyc = \App\Models\Kyc::where('id_company', $company->id_company)->first();
//
//        $min_suku_bunga = 1.5 * $time->duration_time;
//        $max_suku_bunga = 2 * $time->duration_time;
//
//        $data['time_finance_id'] = $type->id;
//        $data['type_finance_id'] = $time->id;
//        $data['amount'] = $request->amount;
//        $data['min_suku_bunga'] = $min_suku_bunga;
//        $data['max_suku_bunga'] = $min_suku_bunga;
//        $data['fee_provisi'] = 0;
//        $data['fee_penalty_delay'] = 0;
//        $data['fee_settled'] = 0;
//        $data['fee_admin'] = 0;
//        $data['fee_insurance'] = 0;
//        $data['status'] = \App\Enums\FinanceOption::status0;
//
//        $finance = $company->finance()->create($data);
//        $path = 'uploads/finance/' . $company->id_company;
//        if (!File::isDirectory(Storage::disk('public')->path($path))) {
//            File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
//        }
//        $image = [];
//        foreach ($request->files as $keyname => $file) {
//            $source = $file;
//            $name = 'finance-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
//            if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
//                $image[$keyname] = Storage::url($path . '/' . $name);
//            }
//        }
//        $verification = $finance->verification()->create($image);
//        $subject = 'Info Tentang Koinworks';
//        $to = $company->email_company;
//        $pdf = null;
//        $template = view('mail.test-email', $data)->render();
//
//        dispatch(new \App\Jobs\SendEmail($subject, $to, $template, $image, $pdf, $data));
////        $this->mailBisdev($company, $finance->id);
////        $this->mailProvider($company, $finance->id);
//        \DB::commit();
//        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
//        $newCompany = $company;
//        $loc = \Stevebauman\Location\Facades\Location::get($ip);
//        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
//        $content = '**New Finance ' . $type->categoryType->name_finance . ' ' . Carbon::now()->format('d M Y H:i:s') . '**';
//        $content .= '```';
//        $content .= "Company Name    : " . $newCompany->company_name . "\n";
//        $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
//        $content .= "Email Company   : " . $newCompany->email_company . "\n";
//        $content .= "Category Finance : " . $type->categoryType->name_finance . "\n";
//        $content .= "IP Address      : " . $ip . "\n";
//        $content .= "City name       : " . $loc->cityName . "\n";
//        $content .= "Region Name     : " . $loc->regionName . "\n";
//        $content .= "Country Code    : " . $loc->countryCode . "\n";
//        $content .= '```';
//
//        $this->sendDiscordNotification($content, 'finance');
//    } catch (\Exception $e) {
//        \DB::rollback();
//        return apiResponse(500, '', getException($e));
//    }
//});

//Route::get('/woowa', function (Request $request, \App\Services\ProductService $productService) {
//    $order = 'OFINV200403373284';
//    $res = $productService->sendWACustomer($order);
//    return apiResponse(200, 'OK', $res);
//});

Route::match(['post', 'get'], 'woo-wa', 'Api\Bot\InboxController@index');

Route::get('otp', 'Api\Bot\InboxController@sendSMS');
Route::post('hhbk', 'Backoffice\Hhbk\HhbkController@importExcell');

Route::post('mde', function (Request $request) {
    $headers = array(
        'Content-Type:application/json'
    );
    $method = "POST";
    $content = '**New MDE Register**';

    $content .= '```';
    $content .= "First Name         : " . $request->input('names')['first_name'] . "\n";
    $content .= "Last Name          : " . $request->input('names')['last_name'] . "\n";
    $content .= "Email              : " . $request->input('email') . "\n";
    $content .= "Phone              : " . $request->input('phone') . "\n";
    $content .= "Company name       : " . $request->input('input_text') . "\n";
    $content .= "web                : " . $request->input('url') . "\n";
    $content .= "Alamat             : " . $request->input('address_1')['address_line_1'] . ' ' . $request->input('address_line_1') . "\n";
    $content .= "Kota               : " . $request->input('address_1')['city'] . "\n";
    $content .= "Provinsi           : " . $request->input('address_1')['state'] . "\n";
    $content .= "Kode POS           : " . $request->input('address_1')['zip'] . "\n";
    $content .= "Kode Negara        : " . $request->input('address_1')['country'] . "\n";

    $content .= '```';
    $data['content'] = sprintf('%s',$content);

    $data = json_encode($data);
    $url = 'https://discordapp.com/api/webhooks/735042839544856657/KZOlAnwB5IVChR7hjRMbBNNlH8I_TuUA6TVXniN0H-HvDCjVOetS5RSmruRnWIcPNFHe';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_exec($ch);
    curl_close($ch);
});

Route::post('gap', function (Request $request) {
    $headers = array(
        'Content-Type:application/json'
    );
    $method = "POST";
    $content = '**New GAP Register**';

    $content .= '```';
    $content .= "First Name         : " . $request->input('names')['first_name'] . "\n";
    $content .= "Last Name          : " . $request->input('names')['last_name'] . "\n";
    $content .= "Email              : " . $request->input('email') . "\n";
    $content .= "Phone              : " . $request->input('phone') . "\n";
    $content .= "Alamat             : " . $request->input('address_1')['address_line_1'] . ' ' . $request->input('address_line_1') . "\n";
    $content .= "Kota               : " . $request->input('address_1')['city'] . "\n";
    $content .= "Provinsi           : " . $request->input('address_1')['state'] . "\n";
    $content .= "Kode Negara        : " . $request->input('address_1')['country'] . "\n";
    $content .= "Alasan Bergabung   : " . $request->input('description') . "\n";

    $content .= '```';
    $data['content'] = sprintf('%s',$content);

    $data = json_encode($data);
    $url = 'https://discordapp.com/api/webhooks/736089200713138216/Vy2IUlBSyz9G_gv-Q_40Ffpx2h8bre8tagZGda0FXK7OjecEsTDKB82ZNhgppdzgrQF4';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_exec($ch);
    curl_close($ch);
});

Route::post('ghs', function (Request $request) {
    $headers = array(
        'Content-Type:application/json'
    );
    $method = "POST";
    $content = '**New GHS Registration**';

    $content .= '```';
    $content .= "Full Name          : " . $request->input('full_name')['first_name'] . "\n";
    $content .= "Email              : " . $request->input('email') . "\n";
    $content .= "Phone              : " . $request->input('phone') . "\n";
    $content .= "Nama penginapan    : " . $request->input('lodging_name') . "\n";
    $content .= "Tipe penginapan    : " . $request->input('lodging_type') . "\n";
    $content .= "Nama Paket         : " . $request->input('package') . "\n";
    $content .= "Alamat             : " . $request->input('address')['address_line_1'] . ' ' . $request->input('address_line_1') . "\n";
    $content .= "Kota               : " . $request->input('address')['city'] . "\n";
    $content .= "Provinsi           : " . $request->input('address')['state'] . "\n";
    $content .= "Pesan              : " . $request->input('message') . "\n";

    $content .= '```';
    $content.= '<@429221162795532298>, <@531939209217310720>, <@433567414366765067>, <@244780919901126656>, <@532036580970463242>';
    $data['content'] = sprintf('%s',$content);

    $data = json_encode($data);
    $url = 'https://discordapp.com/api/webhooks/742010672963125248/uNSqkzRNcpsk9K14Uadm2JVU-Mh8_W6LWN84zOsroO6EM_EzQwNwycoP7TtAZjJF8dIg';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_exec($ch);
    curl_close($ch);
});