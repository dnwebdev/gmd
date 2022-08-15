<?php

use App\Jobs\SendEmail;
use App\Models\CategoryPayment;
use App\Models\Company;
use App\Models\ListPayment;
use App\Models\Order;
use Carbon\Carbon;

Route::group(['middleware' => ['b2b', 'cors']], function () {
    Route::get('/faq', 'Landing\HomePageCtrl@faqLanding')->name('memoria.faq');
    Route::get('/partner-with-us', 'Landing\HomePageCtrl@partnerWithUs')->name('memoria.partner');
});

Route::get('/', 'Landing\HomePageCtrl@index')->name('memoria.home');
Route::get('/ambilKota', 'Landing\HomePageCtrl@ambilKota')->name('memoria.ambilKota');
Route::post('change-language', 'LanguageController@changeLanguage')->name('general:change-language');
// Login
//    Route::get('agent/login', 'AuthController@agent_login')->name('agent.login');
Route::get('agent/logout', 'AuthController@agent_logout')->name('agent.logout');

Route::group(['middleware' => 'guest:web'], function () {
    Route::post('agent/login', 'AuthController@agent_login_submit')->name('agent.login.submit');
    Route::get('agent/login', ['as' => 'login', 'uses' => 'AuthController@agent_login']);
    //    Route::post('agent/login', ['as' => 'login-post', 'uses' => 'AuthController@agent_login_submit']);
//    Route::get('auth/register/otp', 'Company\Register\RegisterCtrl@viewActivate');
//    Route::post('auth/register/otp', 'Company\Register\RegisterCtrl@activateOtp')->name('do-otp-register');
    Route::post('api/new-register', 'Api\Auth\NewAuthController@register');
    Route::post('api/send-otp', 'Api\Auth\NewAuthController@requestOTP');
    Route::post('api/validate-otp', 'Api\Auth\NewAuthController@validateOTP');

    // Password
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset/{token}', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/auth', 'Company\Password\RequestPasswordController@showLinkRequestForm');
    Route::post('password/request/auth', 'Company\Password\RequestPasswordController@requestOTP');
    Route::get('password/otp',
        'Company\Password\RequestPasswordController@viewActivation')->name('api:otp-forgot-password');
    Route::post('password/otp',
        'Company\Password\RequestPasswordController@resetPasswordOTP')->name('api:otp-forgot-password');

    Route::get('agent/register', 'DomainAvailable@index');
    Route::post('agent/register/checkEmail', 'DomainAvailable@checkEmail')->name('agent.register.checkEmail');
    Route::post('agent/register/checkDomain', 'DomainAvailable@checkDomain')->name('agent.register.checkDomain');
    Route::get('agent/register', 'AuthController@agent_register')->name('agent.register');
    Route::post('agent/register', 'AuthController@agent_register_submit')->name('agent.register.submit');
    Route::get('agent/activation/{token}', 'AuthController@agent_activation')->name('agent.registration.activation');
    Route::get('agent/registration/status', 'AuthController@registration_status')->name('agent.registration.status');
    Route::get('activate', 'Company\Register\RegisterCtrl@activateUser');
});

Route::group(['middleware' => 'b2c_company'], function () {
    // Register
    // Route::get('login-testing-hnd', function(){
    //     return view('auth.new-login.login');
    // });
    // Route::get('register-testing-hnd', function(){
    //     return view('auth.new-login.register');
    // });
    // Route::get('activate-testing-hnd/{code}', function($code){
    //     $otp = \App\Models\UserOtp::with('user')->where('shortcode', $code)->first();
    //     return view('auth.new-login.activation',compact('code', 'otp'));
    // });

    Route::get('agent/forgot/password-mail', function () {
        return view('auth.new-login.forgot_password_mail');
    });
    Route::get('agent/forgot/password-phone', function () {
        return view('auth.new-login.forgot_password_phone');
    });
});


//Route::get('/send_email', 'HomeController@send_email')->name('memoria.send_email');
//Route::get('/test_api_mail', 'HomeController@test_api_mail')->name('memoria.test_api_mail');
//Route::post('/api_mail', 'HomeController@api_mail')->name('memoria.api_mail');
Route::post('/sendmail', 'MailController@mail')->name('memoria.sendmail')->middleware('cors');
Route::group(['middleware' => ['b2c']], function () {


// Landing Page
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('test/queue', 'HomeController@testQueue')->name('queue');

// Mailing for landing page

    Route::get('/change-bank-request',
        'AcceptChangeBankRequestController@changeBankAccountRequestAction')->name('memoria.bank.change.action');


// Address Search
    Route::get('product/', 'CompanyProductController@searchAjax')->name('product.search');
    Route::get('countries/', 'LocationController@countries')->name('countries.search');
    Route::get('cities/', 'LocationController@cities')->name('cities.search');
    Route::get('state/', 'LocationController@state')->name('state.search');


// Order

    Route::post('company/order/calculate-amount',
        'CompanyOrderController@get_total_amount')->name('company.order.calculate');
    Route::resource('company/order/', 'CompanyOrderController',
        [
            'names' => [
                'index' => 'company.order.index',
                'create' => 'company.order.create',
                'store' => 'company.order.store',
            ]
        ]);
    Route::get('company/order', 'Company\Order\OrderCtrl@index')->name('company.order.index');
    Route::get('company/order/export', 'Company\Order\OrderCtrl@exportData')->name('company.order.export');
    Route::get('company/order/load_more/{offset?}',
        'CompanyOrderController@load_more')->name('company.order.load_more');
    Route::get('company/order/{id}/edit', 'CompanyOrderController@edit')->name('company.order.edit');
    Route::post('company/order/{id}/edit-status',
        'CompanyOrderController@editStatus')->name('company.order.edit-status');
    Route::put('company/order/{id}/update', 'CompanyOrderController@update')->name('company.order.update');
    Route::post('company/order/validate_schedule',
        'CompanyOrderController@validate_schedule')->name('company.order.validate_schedule');
    Route::post('company/order/add-guide', 'Company\Order\OrderCtrl@saveGuide')->name('company.order.saveGuide');
    Route::post('company/order/delete-guide', 'Company\Order\OrderCtrl@deleteGuide')->name('company.order.deleteGuide');
    Route::post('company/order/send-guide',
        'Company\Order\OrderCtrl@sendDetailOrderEmail')->name('company.order.sendGuide');
    Route::get('company/order/detail/{invoice_no}/export',
        'CompanyOrderController@exportCustomDetail')->name('company.order.export_custom_detail');
    Route::get('company/order/detail/{invoice_no}/download-attachment',
        'CompanyOrderController@downloadDetailAttachment')->name('company.order.download_custom_detail');

    Route::post('company/order/{id}/status-manual-transfer', 'CompanyOrderController@editManualTransfer')->name('company.order.status_manual_transfer');

// Operator controller
    Route::group(['middleware' => ['auth', 'active', 'b2c_company']], function () {

        Route::get('achievement/progress/business-profile', 'Api\Web\Gamification\BusinessProfileController@progress');
        Route::get('achievement/progress/business-profile/incomplete',
            'Api\Web\Gamification\BusinessProfileController@current');
        Route::post('achievement/progress/business-profile/update/company-type',
            'Api\Web\Gamification\BusinessProfileController@updateBusinessType');
        Route::post('achievement/progress/business-profile/update/about-company',
            'Api\Web\Gamification\BusinessProfileController@updateAboutCompany');
        Route::post('achievement/progress/business-profile/update/company-address',
            'Api\Web\Gamification\BusinessProfileController@updateCompanyAddress');
        Route::post('achievement/progress/business-profile/update/company-contact',
            'Api\Web\Gamification\BusinessProfileController@updateContact');
        Route::post('achievement/progress/business-profile/update/company-logo',
            'Api\Web\Gamification\BusinessProfileController@updateLogo');
        Route::post('achievement/progress/business-profile/update/company-banner',
            'Api\Web\Gamification\BusinessProfileController@updateBanner');
        Route::post('achievement/progress/business-profile/update/company-seo',
            'Api\Web\Gamification\BusinessProfileController@updateSEO');
        Route::post('achievement/progress/business-profile/update/company-bank',
            'Api\Web\Gamification\BusinessProfileController@updateBank');
        Route::post('achievement/progress/business-profile/update/company-kyc',
            'Api\Web\Gamification\BusinessProfileController@updateKYC');


        Route::get('achievement/progress/business-profile/getNext',
            'Api\Web\Gamification\BusinessProfileController@getNext');


        // Dashboard
        Route::get('company/dashboard/', 'CompanyController@dashboard')->name('company.dashboard');
        Route::get('company/dashboard/report', 'CompanyController@get_report')->name('company.dashboard.report');

        // Money
        Route::get('company/balance/total',
            'CompanyBalanceController@get_total_balance')->name('company.balance.get_balance');
        Route::get('company/bank/create', 'BankListController@index');
        Route::post('company/bank/create', 'BankListController@index');
        Route::resource('company/bank', 'CompanyBankController',
            [
                'names' => [
                    'index' => 'company.bank.index',
                    'create' => 'company.bank.create',
                    'store' => 'company.bank.store',
                    'edit' => 'company.bank.edit',
                    'update' => 'company.bank.update',

                ]
            ]);
        Route::resource('company/wallet', 'CompanyWalletController',
            [
                'names' => [
                    'index' => 'company.wallet.index',
                    'create' => 'company.wallet.create',
                    'store' => 'company.wallet.store',
                    'edit' => 'company.wallet.edit',
                    'update' => 'company.wallet.update',

                ]
            ]);
        Route::delete('company/bank/{id}/edit', 'CompanyBankController@destroy')->name('company.bank.delete');
        // Notification
        Route::get('company/my_notification',
            'CompanyNotifController@my_notification')->name('company.dashboard.my_notification');
        Route::get('company/notification/{id}',
            'CompanyNotifController@read_notif')->name('company.dashboard.read_notif');

        // Customer
        Route::get('company/find-categories/',
            'CompanyProductCategoryController@find_category')->name('category.search');
        Route::get('company/find-products/', 'CompanyProductController@find_products')->name('product.search');
        Route::get('company/customer/', 'CompanyCustomerController@index')->name('company.customer.index');
        Route::get('company/customer/search-by-email',
            'CompanyCustomerController@search_by_email')->name('customer.search.email');
        Route::get('company/customer/load_more/{offset?}',
            'CompanyCustomerController@load_more')->name('company.customer.load_more');

        // Category, Mark, Extra
        Route::resource('company/product-category', 'CompanyProductCategoryController',
            [
                'names' => [
                    'index' => 'company.product.category.index',
                    'create' => 'company.product.category.create',
                    'store' => 'company.product.category.store',
                    'edit' => 'company.product.category.edit',
                    'update' => 'company.product.category.update',

                ]
            ]);
        Route::resource('company/mark', 'CompanyMarkController',
            [
                'names' => [
                    'index' => 'company.mark.index',
                    'create' => 'company.mark.create',
                    'store' => 'company.mark.store',
                    'edit' => 'company.mark.edit',
                    'update' => 'company.mark.update',

                ]
            ]);
        Route::delete('company/extras/image/delete/{id}',
            'CompanyExtraController@delete_image')->name('company.extra.delete_image');
        Route::post('company/extra/search', 'CompanyExtraController@search')->name('company.extra.search');
        Route::resource('company/extras', 'CompanyExtraController',
            [
                'names' => [
                    'index' => 'company.extra.index',
                    'create' => 'company.extra.create',
                    'store' => 'company.extra.store',
                    'edit' => 'company.extra.edit',
                    'update' => 'company.extra.update',

                ]
            ]);

        // Product
        Route::post('company/product/search', 'CompanyProductController@search')->name('company.product.search');
        Route::get('company/product/select-type',
            'CompanyProductController@select')->name('company.product.select_type');
        Route::get('company/product/create', 'CompanyProductController@create')->name('company.product.create');
        Route::delete('company/product/image/delete/{id}',
            'CompanyProductController@delete_image')->name('company.product.delete_image');
        Route::post('company/product/image/upload/{id}',
            'CompanyProductController@upload_image')->name('company.product.upload_image');
        Route::resource('company/product', 'CompanyProductController',
            [
                'names' => [
                    'index' => 'company.product.index',
                    'edit' => 'company.product.edit',
                    'store' => 'company.product.store',
                    'update' => 'company.product.update',
                ]
            ]);

        Route::post('company/product/delete',
            'Company\Product\ProductCtrl@deleteProduct')->name('company.product.delete');
        Route::post('company/product/update_ota',
            'Company\Product\ProductCtrl@updateOTA')->name('company.product.update_ota');

        Route::get('company/product', 'Company\Product\ProductCtrl@index')->name('company.product.index');
        Route::get('company/product/edit/{id}', 'CompanyProductController@edit')->name('company.product.edit');
        Route::get('company/product/duplicate/{id}',
            'CompanyProductController@duplicateForm')->name('company.product.duplicateForm');
        Route::post('company/product/duplicate/{id}',
            'CompanyProductController@duplicateProduct')->name('company.product.duplicate');
        Route::get('company/product/load_more/{offset?}',
            'CompanyProductController@load_more')->name('company.product.load_more');

        Route::post('company/product/export_orders',
            'CompanyProductController@exportOrders')->name('company.product.export');

        Route::resource('company/withdraw', 'CompanyWithdrawal',
            [
                'names' => [
                    'index' => 'company.withdraw.index',
                    'store' => 'company.withdraw.store',
                    'edit' => 'company.withdraw.edit',
                    'update' => 'company.withdraw.update',

                ]
            ]);

        // Voucher & Tax
        Route::resource('company/tax', 'CompanyTaxController',
            [
                'names' => [
                    'index' => 'company.tax.index',
                    'create' => 'company.tax.create',
                    'store' => 'company.tax.store',
                    'edit' => 'company.tax.edit',
                    'update' => 'company.tax.update',

                ]
            ]);
        Route::get('select2-search-city', 'Company\Ads\AdsSelectCtrl@getSelectCity')->name('select2.city');

        // voucher
        Route::resource('company/voucher', 'CompanyVoucherController',
            [
                'names' => [
                    'index' => 'company.voucher.index',
                    'create' => 'company.voucher.create',
                    'store' => 'company.voucher.store',
                    'edit' => 'company.voucher.edit',
                    'update' => 'company.voucher.update',

                ]
            ]);

        // Profile
        Route::get('company/profile', 'CompanyController@my_profile')->name('company.profile');
        Route::post('company/profile', 'CompanyController@update')->name('company.profile.update');
        Route::resource('company/theme', 'CompanyThemeController',
            [
                'names' => [
                    'index' => 'company.theme.index',
                    'edit' => 'company.theme.edit',
                    'update' => 'company.theme.update',

                ]
            ]);

        // banner
        Route::resource('company/banner', 'CompanyBannerController',
            [
                'names' => [
                    'index' => 'company.banner.index',
                    'create' => 'company.banner.create',
                    'store' => 'company.banner.store',
                    'edit' => 'company.banner.edit',
                    'update' => 'company.banner.update',

                ]
            ]);
        Route::delete('company/banner/image/delete',
            'CompanyBannerController@delete_image')->name('company.banner.delete_image');
        Route::get('company/mail-server', 'CompanyEmailServer@index')->name('company.mail_server.index');
        Route::post('company/mail-server/update', 'CompanyEmailServer@update')->name('company.mail_server.update');

        // Help
        Route::get('company/help/', 'CompanyHelpController@index')->name('company.help.index');
        Route::post('company/help/', 'CompanyHelpController@store')->name('company.help.store');

        // Premium
        Route::resource('company/premium', 'Company\Ads\AdsCtrl', [
            'names' => [
                'index' => 'company.premium.index',
                'create' => 'company.premium.create',
                'store' => 'company.premium.store',
                'edit' => 'company.premium.edit',
                'update' => 'company.premium.update',
            ]
        ]);

        Route::post('company/premium/store/google',
            'Company\Ads\AdsCtrl@storeGoogle')->name('company.premium.store.google');

        Route::post('company/premium/testsubmit', 'Company\Ads\AdsCtrl@testsubmit')->name('company.premium.testsubmit');
        Route::get('company/premium/index/faq', 'Company\Ads\AdsCtrl@faq')->name('company.premium.faq');
        Route::get('company/premium/index/gxp', 'Company\Ads\AdsCtrl@gxp')->name('company.premium.gxp');
        Route::get('myvoucher', 'Company\Ads\AdsCtrl@myvoucher')->name('company.check.myvoucher');
        Route::get('promocode', 'Company\Ads\AdsCtrl@promocode')->name('company.check.promocode');
        Route::get('gxp', 'Company\Ads\AdsCtrl@checkgxp')->name('company.check.gxp');
        Route::get('creditCard', 'Company\Ads\AdsCtrl@creditCard')->name('company.check.creditcard');

        Route::get('invoice-ads/bank-transfer/{invoice}',
            'Company\Ads\AdsCtrl@getDataXendit')->name('invoice-ads.bank-transfer');
        Route::get('invoice-ads/check-order', 'Company\Ads\AdsCtrl@checkDataOrder')->name('invoice-ads.check-order');

        Route::get('invoice-ads/virtual-account/{invoice}',
            'Company\Ads\AdsCtrl@getDataXendit')->name('invoice-ads.virtual-account');
        Route::get('invoice-ads/virtual-account',
            'Company\Ads\AdsCtrl@getDataXendit')->name('invoice-ads.virtual.account');
        Route::get('invoice-ads/order-success', 'Company\Ads\AdsCtrl@successPayment')->name('invoice-ads.success');

        // Offline Order
        Route::get('company/manual-order', 'Company\Offline\OfflineOrderCtrl@index')->name('company.manual.index');
        Route::post('company/manual-order/validate',
            'Company\Offline\OfflineOrderCtrl@stepOneValidation')->name('company.manual.validation');
        Route::get('company/manual-order/view/{id}',
            'Company\Offline\OfflineOrderCtrl@detail')->name('company.manual.detail');
        Route::get('company/manual-order/data',
            'Company\Offline\OfflineOrderCtrl@loadAjaxData')->name('company.manual.data');
        Route::get('company/manual-order/create',
            'Company\Offline\OfflineOrderCtrl@create')->name('company.manual.create');

        Route::post('company/manual-order',
            'Company\Offline\OfflineOrderCtrl@setOrder')->name('company.post.manual-order');
        Route::post('company/resend-manual-order',
            'Company\Offline\OfflineOrderCtrl@resendInvoice')->name('company.post.resend-manual-order');
        Route::post('company/cancel-order',
            'Company\Order\OrderCtrl@cancelInvoice')->name('company.post.cancel-invoice');

        //KYC
        Route::get('company/kyc', 'Company\Kyc\KycCtrl@index')->name('company.kyc.index');
        Route::post('company/kyc', 'Company\Kyc\KycCtrl@update')->name('company.kyc.update');

        Route::get('company/updates', 'Company\Update\UpdateCtrl@index')->name('company.update.index');
        Route::get('company/updates/count',
            'Company\Update\UpdateCtrl@getUnreadNotificationCount')->name('company.update.count');
        Route::get('company/updates/data', 'Company\Update\UpdateCtrl@readData')->name('company.update.data');

        Route::group(['prefix' => 'company/article', 'namespace' => 'Company\Blog'], function () {
            Route::get('/', 'BlogController@index')->name('company.blog.index');
            Route::get('/search', 'BlogController@searchBlog')->name('company.blog.search');
            Route::get('/detail', 'BlogController@detail')->name('company.blog.detail');
        });

        //Koinwork
        Route::get('company/finance', 'Company\Finance\FinanceCtrl@index')->name('company.finance.index');
        Route::post('company/finance/store', 'Company\Finance\FinanceCtrl@store')->name('store.finance');
        Route::get('company/finance/check-finance-kyc', 'Company\Finance\FinanceCtrl@checkKyc')->name('check-finance-kyc');

        Route::get('company/distribution', function(){
            $requestDistribution = \App\Models\DistributionRequest::where('company_id',auth()->user()->company->id_company)->first();
            return view('dashboard.company.distribution.index',compact('requestDistribution'));
        })->name('company.distribution.index');

        Route::get('company/insurance', function(){
            return view('dashboard.company.insurance.index');
        })->name('company.insurance.index');

        Route::post('company/distribution/request','Company\Request\DistributionController@save');
        Route::post('company/insurance/request','Company\Request\InsuranceController@save');
    });

    Route::middleware('cors')->group(function () {
        // API Region
        Route::get('location/countries', 'Api\Region\RegionCtrl@getCountries')->name('location.countries');
        Route::get('location/states', 'Api\Region\RegionCtrl@getStateFromCountry')->name('location.states');
        Route::get('location/cities', 'Api\Region\RegionCtrl@getCityFromState')->name('location.cities');
        Route::get('location/regional', 'Api\Region\RegionCtrl@getDataRegionalFromCityId')->name('location.regional');


        // Customer controller


        Route::get('/heir-provisions', 'Landing\HomePageCtrl@heirProvisions')->name('memoria.heir');
        Route::get('/widget-faq', 'Landing\HomePageCtrl@faqWidget')->name('memoria.widget-faq');

        Route::get('/filter-search-product', 'Landing\HomePageCtrl@getRenderProducts')->name('memoria.render.products');
		//Agar bisa dicopy paste langsung di web
		//Route::get('/filter-search-product/keyword/{keyword}/kat/{kategori}/tag/{tag}/city/{city}/sort/{sort}', //'Landing\HomePageCtrl@getRenderProductsBaru')->name('memoria.render.products.baru');
		//Route::get('/cari', 'Landing\HomePageCtrl@getRenderProductsCari')->name('memoria.render.products.cari');
        Route::post('/', 'Landing\HomePageCtrl@index')->name('memoria.home');
        Route::get('/register-provider',
            'Landing\HomePageCtrl@viewRegister')->name('memoria.register')->middleware('guest:web');
        Route::get('validate/step1',
            'Company\Register\RegisterCtrl@firstStep')->name('memoria.validate.one')->middleware('guest:web');
        Route::get('validate/email',
            'Company\Register\RegisterCtrl@validateEmail')->name('memoria.validate.email')->middleware('guest:web');
        Route::get('validate/phone',
            'Company\Register\RegisterCtrl@validatePhone')->name('memoria.validate.phone')->middleware('guest:web');
        Route::get('validate/domain',
            'Company\Register\RegisterCtrl@validateDomain')->name('memoria.validate.domain')->middleware('guest:web');
        Route::get('do-register-provider',
            'Company\Register\RegisterCtrl@registerCompany')->name('memoria.do.register')->middleware('guest:web');
        Route::get('detail/{slug}', 'HomeController@detail')->name('memoria.detail');
        Route::get('more', 'HomeController@products')->name('memoria.more');
        Route::match(['get', 'post'], 'book', 'HomeController@book')->name('memoria.book');
        Route::match(['get', 'post'], 'inquiry', 'HomeController@inquiry')->name('memoria.inquiry');
        Route::post('send_inquiry', 'HomeController@send_inquiry')->name('memoria.send_inquiry');
        Route::post('validate_schedule', 'HomeController@validate_schedule')->name('memoria.validate_schedule');
        Route::post('create_order', 'HomeController@create_order')->name('memoria.create_order');
        Route::get('retrieve', 'Customer\Retreive\RetreiveCtrl@retreive')->name('memoria.retrieve');
        Route::get('retrieve_booking', 'Customer\Retreive\RetreiveCtrl@getDataRetreive')->name('memoria.retrieve.data');
        Route::get('check_booking',
            'Customer\Retreive\RetreiveCtrl@validBooking')->name('memoria.retrieve.validBooking');
        Route::post('send_invoice', 'HomeController@send_invoice_mail')->name('memoria.send_invoice_mail');
        Route::post('xendit/payment', 'PaymentController@xendit_accept_payment')->name('payment.xendit.payment');
        Route::post('xendit/disbursement',
            'CompanyWithdrawal@xendit_accept_disbursement')->name('withdraw.xendit.payment');
        Route::get('invoice/virtual-account/{invoice}',
            'Customer\HomeCtrl@getDataXendit')->name('invoice.virtual-account');
        Route::get('invoice/check-order', 'Customer\HomeCtrl@checkDataOrder')->name('invoice.check-order');
        Route::get('invoice/redeem-voucher/{invoice}',
            'Customer\HomeCtrl@getDataXendit')->name('invoice.redeem-voucher');
        Route::get('invoice/bank-transfer/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.bank-transfer');
        Route::get('invoice/credit-card/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.credit-card');
        Route::get('invoice/ovo/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.ovo');
        Route::post('invoice/ovo/{invoice}', 'Test\TestCtrl@payOVO')->name('invoice.ovo.pay');
        Route::get('invoice/alfamart/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.alfamart');
        Route::get('invoice/kredivo/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.kredivo');
        Route::get('invoice/dana/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.dana');
        Route::get('invoice/linkaja/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.linkaja');
        Route::post('kredivo/callback', 'Customer\HomeCtrl@kredivoCallback')->name('kredivo.callback');
        Route::post('ewallet/callback', 'Customer\HomeCtrl@ewalletCallback')->name('ewallet.callback');
        Route::get('invoice/order-success', 'Customer\Retreive\RetreiveCtrl@successPayment')->name('invoice.success');
        Route::get('email', 'HomeController@email')->name('memoria.email');
        Route::get('pdf', 'HomeController@generate_pdf')->name('pdf');
        Route::get('schedule', 'Customer\HomeCtrl@validateSchedule')->name('schedule');

        Route::get('check/invoice/midtrans/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('check-invoice.invoice.midtrans');
        Route::get('invoice/indomaret/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.indomaret');
        Route::get('invoice/gopay/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.gopay');
        Route::get('invoice/bca-virtual-account/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.midtrans-virtual-account');
        Route::get('invoice/akulaku/{invoice}', 'Customer\HomeCtrl@getDataXendit')->name('invoice.akulaku');
        Route::get('check-midtrans', 'Customer\HomeCtrl@checkMidtrans')->name('check.midtrans');

        Route::get('product/detail/{slug}', 'Customer\HomeCtrl@detailProduct')->name('product.detail');
        Route::post('product/book/{slug}', 'Customer\HomeCtrl@book')->name('product.book');
        Route::get('product/book/{slug}', function ($slug) {
            return redirect()->route('product.detail', ['slug' => $slug]);
        });
        Route::get('cityByCountry', 'Customer\HomeCtrl@getCityByCountry')->name('city.byCountry');
        Route::get('customer-invoices/{invoice}', 'Customer\HomeCtrl@invoice')->name('customer.invoice');
        Route::post('customer-pay', 'Customer\HomeCtrl@pay')->name('customer.pay');
        Route::get('check-voucher', 'Customer\HomeCtrl@checkVoucher')->name('customer.check.voucher');
        Route::get('check-insurance', 'Customer\InsuranceController@getDataInsurance')->name('customer.check.insurance');
        Route::get('payment/virtual-account',
            'Customer\HomeCtrl@payByVirtualAccount')->name('customer.pay.virtual_account');

        Route::get('send_success/{company}/{id}', 'Customer\HomeCtrl@send_invoice');
        Route::match(['get', 'post'], 'test/post-cc', 'Test\TestCtrl@test');
        Route::get('test/view', 'Test\TestCtrl@testView');
        Route::get('test/cc', 'Test\TestCtrl@testViewCC');
        Route::get('check/cc', 'Test\TestCtrl@checkCC');

        Route::post('customer-transfer', 'Customer\HomeCtrl@confirmTransfer')->name('customer.transfer');

    });


});

Route::group(['middleware' => 'b2c'], function () {
    Route::get('otp/{shortcode}', 'Company\Register\RegisterCtrl@viewActivate');
    Route::get('map', 'Api\Auth\RegisterController@kirim');
    Route::get('c', function () {
        return view('welcome');
    });
    Route::get('digitalregs', function () {
//    $list = ListPayment::with(['companies'=>function($company){
//        $company->where('id_company',52);
//    }])->where('status',1)->get();
//    dd($list->toArray());

        foreach (ListPayment::all() as $item) {
            $item->companies()->sync(Company::all());
//        $company = Company::find($item->companies()->first()->id_company);
            foreach ($item->companies as $data) {
                if (in_array($item->code_payment, ['credit_card', 'dana', 'linkaja', 'indomaret', 'gopay', 'bca_va','akulaku','ovo_live'])) {
                    $item->companies()->updateExistingPivot($data->id_company, ['charge_to' => 1]);
                }
            }
        }
    });

    Route::get('/mid', function () {
//    \App\Models\Order::whereHas('payment', function ($paymentQuery) {
//        $paymentQuery->where('payment_gateway', 'like', 'Midtrans%')->where('status', 'PENDING');
//    })->whereStatus(1)->where('settlement_on','<=',Carbon::now()->toDateTimeString())->update(['status'=>'PAID']);
        $settlementDay = 6;

        $pembayaran = Carbon::now()
            ->addDays($settlementDay);
        $hari = $pembayaran->format('N');
        switch ($hari) {
            case 6:
                $pembayaran->addDays(2);
                $tes = 'dsadsa';
                break;
            case 7:
                $pembayaran->addDay(1);
        }
        dd($pembayaran->toDateTimeString());
        skema($pembayaran->format('d M Y'));
    });
    Route::get('karisma', function (\Illuminate\Http\Request $request) {
//        Company::withoutGlobalScopes()->chunk(100, function ($companies) {
//            foreach ($companies as $company):
//                $company->agent->update(['phone_code' => 62]);
//                if ($company->agent->phone && $company->agent->phone != '000000000000'):
//                    if (substr($company->agent->phone, 0, 2) == '62'):
//                        $company->agent->update(['phone' => str_replace_first('62', '', $company->agent->phone)]);
//                    elseif (substr($company->agent->phone, 0, 2) == '00'):
//                    else:
//                        $company->agent->update(['phone' => (int)$company->agent->phone]);
//                    endif;
//                endif;
//            endforeach;
//        });
//        foreach (\App\Models\UserAgent::all() as $agent):
//            skema(substr($agent->phone, 0, 2));
//        endforeach;

        $order = Order::find('INV5233115457517101877');
        $http = \request()->isSecure()?'https://':'http://';
        $message =[];
        $message[]='Order dengan Invoice ' .$order->invoice_no.' telah dibatalkan oleh Provider';
        $message[] = 'Silahkan periksa '.$http.$order->company->domain_memoria.'/retrieve_booking?no_invoice='.$order->invoice_no;
        $message = sprintf('%s',implode('\n',$message));
        $res = \Kayiz\Woowa::SendMessageAsync()->setPhone('6285712299001')->setMessage($message)->prepareContent()->send()->getResponse();
        dd($res['status']);
     });
});

Route::get('uuid', function (){
    $data = [];
    $uuid = \Ramsey\Uuid\Uuid::uuid1(time())->toString();
    $data[] = $uuid;
    $uuid = \Ramsey\Uuid\Uuid::uuid1(time())->toString();
    $data[] = $uuid;
    $uuid = \Ramsey\Uuid\Uuid::uuid1(time())->toString();
    $data[] = $uuid;

    skema($data);

    $col = rsort($data);
    skema($data);
});
Route::get('qr-code', function (\Illuminate\Http\Request $request){
   if ($invoice = Order::find($request->get('invoice'))){
       $scann = new \Milon\Barcode\DNS2D();
       echo $scann->getBarcodeHTML($invoice->invoice_no, 'QRCODE');
   }
});
