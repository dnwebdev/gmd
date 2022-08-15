<?php
/**
 * Created by PhpStorm.
 * User: hkari
 * Date: 2/27/2019
 * Time: 9:47 AM
 */

use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

Route::group(['middleware' => 'b2c'], function () {


    Route::get('/', 'Home\DashboardCtrl@index')->name('admin:dashboard');


    Route::group(['middleware' => 'guest:admin'], function () {
        Route::get('login', 'Auth\LoginCtrl@showLoginForm')->name('admin:login');
        Route::post('login', 'Auth\LoginCtrl@login')->name('admin:do.login');
        Route::post('forgot-password',
            'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin:request-reset-password');
        Route::get('password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin:show.reset');
        Route::post('password/{token}', 'Auth\ResetPasswordController@reset')->name('admin:do.reset');
    });
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('data-statistic/{range?}/{n?}', 'Home\DashboardCtrl@statistics')->name('admin:dashboard.statistics');
        Route::get('top-provider/{day?}', 'Home\DashboardCtrl@topProvider')->name('admin:dashboard.top.provider');
//    Route::get('/','Home\StatisticCtrl@index')->name('admin:dashboard');
        Route::get('/statistic', 'Home\StatisticCtrl@index')->name('admin:statistic');
        Route::get('/statistic-data', 'Home\StatisticCtrl@loadData')->name('admin:statistic-data');
        Route::get('/logout', 'Auth\LoginCtrl@logout')->name('admin:logout');
        Route::get('/profile', 'Profile\ProfileCtrl@index')->name('admin:profile');
        Route::post('/profile', 'Profile\ProfileCtrl@update')->name('admin:profile.update');

        // Route::group(['prefix' => 'profile', 'as' => 'admin:profile.', 'namespace' => 'Profile'], function () {
        //     Route::get('', 'ProfileCtrl@index')->name('index');
        //     Route::post('', 'ProfileCtrl@update')->name('update');
        // });
        Route::group([
            'prefix' => 'business-category',
            'as' => 'admin:master.business-category.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'BusinessCategoryCtrl@index')->name('index');
            Route::get('data', 'BusinessCategoryCtrl@loadData')->name('data');
            Route::post('add', 'BusinessCategoryCtrl@store')->name('save');
            Route::post('update', 'BusinessCategoryCtrl@update')->name('update');
            Route::post('delete', 'BusinessCategoryCtrl@delete')->name('delete');
        });
        Route::group([
            'prefix' => 'guide-language',
            'as' => 'admin:master.guide-language.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'GuideLanguageCtrl@index')->name('index');
            Route::get('data', 'GuideLanguageCtrl@loadData')->name('data');
            Route::post('add', 'GuideLanguageCtrl@store')->name('save');
            Route::post('update', 'GuideLanguageCtrl@update')->name('update');
            Route::post('delete', 'GuideLanguageCtrl@delete')->name('delete');
        });

        Route::group([
            'prefix' => 'city',
            'as' => 'admin:master.city.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'CityController@index')->name('index');
            Route::get('data', 'CityController@loadData')->name('data');
            Route::get('changeCountry', 'CityController@changeCountry')->name('change.country');
            Route::post('update', 'CityController@update')->name('update');
        });

        Route::group([
            'prefix' => 'state',
            'as' => 'admin:master.state.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'StateController@index')->name('index');
            Route::get('data', 'StateController@loadData')->name('data');
            Route::post('update', 'StateController@update')->name('update');

        });
        Route::group([
            'prefix' => 'product-tag',
            'as' => 'admin:master.product-tag.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ],
            function () {
                Route::get('', 'ProductTagCtrl@index')->name('index');
                Route::get('data', 'ProductTagCtrl@loadData')->name('data');
                Route::post('add', 'ProductTagCtrl@store')->name('save');
                Route::post('update', 'ProductTagCtrl@update')->name('update');
                Route::post('delete', 'ProductTagCtrl@delete')->name('delete');
                Route::get('list/{tag}', 'ProductTagCtrl@ListTag')->name('list-tag');
            });
        Route::group([
            'prefix' => 'addon',
            'as' => 'admin:master.addon.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'AddonCtrl@index')->name('index');
            Route::get('data', 'AddonCtrl@loadData')->name('data');
            Route::post('add', 'AddonCtrl@store')->name('save');
            Route::post('update', 'AddonCtrl@update')->name('update');
            Route::post('delete', 'AddonCtrl@delete')->name('delete');
        });

        Route::group([
            'prefix' => 'transaction',
            'as' => 'admin:master.transaction.',
            'namespace' => 'Order',
            'middleware' => 'admin:customer-support'
        ], function () {
            Route::get('data/{type?}', 'OrderCtrl@loadData')->name('data');
            Route::get('{type?}', 'OrderCtrl@index')->name('index');
            Route::get('{invoice}/detail', 'OrderCtrl@detail')->name('detail');
            Route::post('export', 'OrderCtrl@export')->name('export');

        });

        Route::group([
            'prefix' => 'transaction-manual',
            'as' => 'admin:master.transaction-manual.',
            'namespace' => 'Order',
            'middleware' => 'admin:customer-support'
        ], function () {
            Route::get('data/{type?}', 'ManualTransferCtrl@loadData')->name('data');
            Route::get('{type?}', 'ManualTransferCtrl@index')->name('index');
            Route::get('{invoice}/detail', 'ManualTransferCtrl@detail')->name('detail');
            Route::post('export', 'ManualTransferCtrl@export')->name('export');
            Route::post('{invoice}/status-manual-transfer', 'ManualTransferCtrl@editManualTransfer')->name('status_manual_transfer');
        });

        Route::group([
            'prefix' => 'finance',
            'as' => 'admin:master.finance.',
            'namespace' => 'Finance',
            'middleware' => 'admin:customer-support'
        ], function () {
            Route::get('', 'ListFinanceCtrl@index')->name('index');
            Route::get('data', 'ListFinanceCtrl@loadData')->name('data');
            Route::get('{id}/detail', 'ListFinanceCtrl@detail')->name('detail');
            Route::post('export', 'ListFinanceCtrl@export')->name('export');
            Route::get('download/{id}', 'ListFinanceCtrl@downloadAllFile')->name('download-all');
            Route::get('download-pdf/{id}', 'ListFinanceCtrl@downloadPdf')->name('download-pdf');
        });

        Route::group([
            'prefix' => 'association',
            'as' => 'admin:master.association.',
            'namespace' => 'Master',
            'middleware' => 'admin:customer-support|business-development|ux'
        ], function () {
            Route::get('', 'AssociationCtrl@index')->name('index');
            Route::get('data', 'AssociationCtrl@loadData')->name('data');
            Route::post('add', 'AssociationCtrl@store')->name('save');
            Route::post('update', 'AssociationCtrl@update')->name('update');
            Route::post('delete', 'AssociationCtrl@delete')->name('delete');
            Route::get('search-provider', 'AssociationCtrl@searchProvider')->name('search-provider');
            Route::post('save-provider', 'AssociationCtrl@saveProvider')->name('save-provider');
        });

        Route::group([
            'prefix' => 'premium',
            'as' => 'admin:premium.premium.',
            'namespace' => 'Premium',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::get('', 'PremiumCtrl@index')->name('index');
            Route::get('data', 'PremiumCtrl@loadData')->name('data');
            Route::get('detail/{id}', 'PremiumCtrl@detailPremium')->name('detail-premium');
            Route::post('add', 'PremiumCtrl@store')->name('save');
            Route::post('update', 'PremiumCtrl@update')->name('update');
            Route::post('delete', 'PremiumCtrl@delete')->name('delete');
            Route::get('download/{id}', 'PremiumCtrl@download')->name('premium-download');
        });

        Route::group([
            'prefix' => 'premium/promo-code',
            'as' => 'admin:premium.promo-code.',
            'namespace' => 'Premium',
            'middleware' => 'admin:customer-support|business-development'
        ],
            function () {
                Route::get('', 'PromoCodeCtrl@index')->name('index');
                Route::get('add', 'PromoCodeCtrl@add')->name('add');
                Route::post('add', 'PromoCodeCtrl@save')->name('save');
                Route::post('delete', 'PromoCodeCtrl@delete')->name('delete');
                Route::get('data', 'PromoCodeCtrl@loadAjaxData')->name('data');

                Route::get('edit/{id}', 'PromoCodeCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'PromoCodeCtrl@update')->name('update');
            });

        Route::group([
            'prefix' => 'promo-code-gomodo',
            'as' => 'admin:voucher-gomodo.',
            'namespace' => 'Voucher',
            'middleware' => 'admin:customer-support|business-development'
        ],
            function () {
                Route::get('', 'VoucherGomodoCtrl@index')->name('index');
                Route::get('search-provider', 'VoucherGomodoCtrl@searchProvider')->name('search-provider');
                Route::get('add', 'VoucherGomodoCtrl@add')->name('add');
                Route::post('add', 'VoucherGomodoCtrl@save')->name('save');
                Route::post('delete', 'VoucherGomodoCtrl@delete')->name('delete');
                Route::get('data', 'VoucherGomodoCtrl@loadAjaxData')->name('data');
                Route::get('edit/{id}', 'VoucherGomodoCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'VoucherGomodoCtrl@update')->name('update');
                Route::post('enable}', 'VoucherGomodoCtrl@enable')->name('enable');
                Route::post('disable', 'VoucherGomodoCtrl@disable')->name('disable');
                Route::post('reimbursement', 'VoucherGomodoCtrl@reimbursementGomodo')->name('reimbursement');
            });

        Route::group([
            'prefix' => 'payment/list-payment',
            'as' => 'admin:master.list-payment.',
            'namespace' => 'Master',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'ListPaymentCtrl@index')->name('index');
                Route::get('data', 'ListPaymentCtrl@loadData')->name('data');
                Route::get('create', 'ListPaymentCtrl@create')->name('create');
                Route::post('add', 'ListPaymentCtrl@save')->name('save');
                Route::post('delete', 'ListPaymentCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'ListPaymentCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'ListPaymentCtrl@update')->name('update');
                Route::post('active', 'ListPaymentCtrl@active')->name('active');
                Route::post('nonactive', 'ListPaymentCtrl@nonactive')->name('nonactive');
            });

        Route::group([
            'prefix' => 'payment/provider-manual-transfer',
            'as' => 'admin:master.provider-manual-transfer.',
            'namespace' => 'Master',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'ProviderManualTransferCtrl@index')->name('index');
                Route::get('data', 'ProviderManualTransferCtrl@loadData')->name('data');
                Route::get('create', 'ProviderManualTransferCtrl@create')->name('create');
                Route::post('add', 'ProviderManualTransferCtrl@save')->name('save');
                Route::post('delete', 'ProviderManualTransferCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'ProviderManualTransferCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'ProviderManualTransferCtrl@update')->name('update');
                Route::get('download/{id}', 'ProviderManualTransferCtrl@download')->name('download');
            });

        Route::group([
            'prefix' => 'unit_name',
            'as' => 'admin:master.unit_name.',
            'namespace' => 'Master',
            'middleware' => 'admin:super-admin'
        ], function () {
            Route::get('', 'UnitNameCtrl@index')->name('index');
            Route::get('data', 'UnitNameCtrl@loadData')->name('data');
            Route::post('add', 'UnitNameCtrl@save')->name('save');
            Route::post('delete', 'UnitNameCtrl@delete')->name('delete');
            Route::post('edit', 'UnitNameCtrl@update')->name('update');
        });

        Route::group([
            'prefix' => 'insurance/list',
            'as' => 'admin:insurance.list.',
            'namespace' => 'Insurance',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'InsuranceController@index')->name('index');
                Route::get('data', 'InsuranceController@loadData')->name('data');
                Route::get('create', 'InsuranceController@create')->name('create');
                Route::post('add', 'InsuranceController@store')->name('store');
                Route::post('delete', 'InsuranceController@delete')->name('delete');
                Route::get('edit/{id}', 'InsuranceController@edit')->name('edit');
                Route::post('edit/{id}', 'InsuranceController@update')->name('update');
                Route::post('set-status', 'InsuranceController@setStatus')->name('status');
//            Route::post('nonactive', 'InsuranceController@nonactive')->name('nonactive');
            });

        Route::group([
            'prefix' => 'insurance/data-customer',
            'as' => 'admin:insurance.data-customer.',
            'namespace' => 'Insurance',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'DataInsuranceController@index')->name('index');
                Route::get('data', 'DataInsuranceController@loadData')->name('data');
                Route::get('detail-data/{id}', 'DataInsuranceController@detail')->name('detail-insurance');
                Route::post('export', 'DataInsuranceController@export')->name('export');
            });

        Route::group([
            'prefix' => 'payment/category-payment',
            'as' => 'admin:master.category-payment.',
            'namespace' => 'Master',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'CategoryPaymentCtrl@index')->name('index');
                Route::get('data', 'CategoryPaymentCtrl@loadData')->name('data');
                Route::post('add', 'CategoryPaymentCtrl@store')->name('save');
                Route::post('update', 'CategoryPaymentCtrl@update')->name('update');
                Route::post('delete', 'CategoryPaymentCtrl@delete')->name('delete');
            });

        Route::group([
            'prefix' => 'payment/company-payment',
            'as' => 'admin:master.company-payment.',
            'namespace' => 'Master',
            'middleware' => 'admin:super-admin'
        ],
            function () {
                Route::get('', 'CompanyPaymentCtrl@index')->name('index');
                Route::get('data', 'CompanyPaymentCtrl@loadData')->name('data');
                Route::get('create', 'CompanyPaymentCtrl@create')->name('create');
                Route::post('add', 'CompanyPaymentCtrl@save')->name('save');
                Route::post('delete', 'CompanyPaymentCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'CompanyPaymentCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'CompanyPaymentCtrl@update')->name('update');
                Route::get('search-provider', 'CompanyPaymentCtrl@searchProvider')->name('search-provider');
                Route::post('active', 'CompanyPaymentCtrl@active')->name('active');
                Route::post('nonactive', 'CompanyPaymentCtrl@nonactive')->name('nonactive');
            });

        Route::group([
            'prefix' => 'updates',
            'as' => 'admin:updates.',
            'namespace' => 'Update',
            'middleware' => 'admin:customer-support|business-development|content-writer'
        ], function () {
            Route::get('', 'UpdateCtrl@index')->name('index');
            Route::get('add', 'UpdateCtrl@add')->name('add');
            Route::post('add', 'UpdateCtrl@save')->name('save');
            Route::post('delete', 'UpdateCtrl@delete')->name('delete');
            Route::get('data', 'UpdateCtrl@loadAjaxData')->name('data');
            Route::get('edit/{id}', 'UpdateCtrl@edit')->name('edit');
            Route::post('edit/{id}', 'UpdateCtrl@update')->name('update');
        });

        Route::group([
            'prefix' => 'providers',
            'as' => 'admin:providers.',
            'namespace' => 'Company',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::get('', 'CompanyCtrl@index')->name('index');
            Route::get('add', 'CompanyCtrl@add')->name('add');
            Route::post('add', 'CompanyCtrl@save')->name('save');
            Route::post('delete', 'CompanyCtrl@delete')->name('delete');
            Route::get('data', 'CompanyCtrl@loadAjaxData')->name('data');
            Route::get('edit/{id}', 'CompanyCtrl@edit')->name('edit');
            Route::post('edit-company/{id}', 'CompanyCtrl@saveCompany')->name('update-company');
            Route::post('edit-user/{id}', 'CompanyCtrl@saveUser')->name('update-user');
            Route::post('edit-bank/{id}', 'CompanyCtrl@saveBank')->name('update-bank');
            Route::post('save-association/{id}', 'CompanyCtrl@saveAssociation')->name('save-association');
            Route::post('update-association/{id}', 'CompanyCtrl@saveAssociation')->name('update-association');
            Route::post('delete-association/{id}', 'CompanyCtrl@deleteAssociation')->name('delete-association');
            Route::post('update-google-widget/{id}', 'CompanyCtrl@saveGoogleWidget')->name('update-google-widget');
            Route::get('login-as-user/{id}', 'CompanyCtrl@loginAsUser')->name('login-as-user');
            Route::post('export', 'CompanyCtrl@export')->name('export');
        });

        Route::group([
            'prefix' => 'b2b/blog/post',
            'as' => 'admin:b2b.post.',
            'namespace' => 'Company\Blog',
            'middleware' => 'admin:content-writer'
        ], function () {
            Route::get('', 'PostCtrl@index')->name('index');
            Route::get('data', 'PostCtrl@loadData')->name('data');
            Route::get('create', 'PostCtrl@create')->name('create');
            Route::post('add', 'PostCtrl@save')->name('save');
            Route::post('delete', 'PostCtrl@delete')->name('delete');
            Route::get('edit/{id}', 'PostCtrl@edit')->name('edit');
            Route::post('edit/{id}', 'PostCtrl@update')->name('update');
            Route::post('active', 'PostCtrl@active')->name('active');
            Route::post('nonactive', 'PostCtrl@nonactive')->name('nonactive');
        });

        Route::group([
            'prefix' => 'b2b/blog/category',
            'as' => 'admin:b2b.category.',
            'namespace' => 'Company\Blog',
            'middleware' => 'admin:content-writer'
        ], function () {
            Route::get('', 'CategoryCtrl@index')->name('index');
            Route::get('data', 'CategoryCtrl@loadData')->name('data');
            Route::post('add', 'CategoryCtrl@store')->name('save');
            Route::post('update', 'CategoryCtrl@update')->name('update');
            Route::post('delete', 'CategoryCtrl@destroy')->name('delete');

        });

        Route::group([
            'prefix' => 'b2b/blog/tags',
            'as' => 'admin:b2b.tag.',
            'namespace' => 'Company\Blog',
            'middleware' => 'admin:content-writer'
        ], function () {
            Route::get('', 'TagCtrl@index')->name('index');
            Route::get('data', 'TagCtrl@loadData')->name('data');
            Route::post('add', 'TagCtrl@store')->name('save');
            Route::post('update', 'TagCtrl@update')->name('update');
            Route::post('delete', 'TagCtrl@destroy')->name('delete');
        });


        Route::group([
            'prefix' => 'product',
            'as' => 'admin:product.',
            'namespace' => 'Product',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::get('data/{tag?}', 'ProductController@loadData')->name('data');
            Route::get('detail/{id}', 'ProductController@detail')->name('detail');
            Route::post('detail/{id}', 'ProductController@update')->name('update');
            Route::get('change-state', 'ProductController@getCitiesFromState')->name('change-state');
            Route::get('change-country', 'ProductController@getStateFromCountry')->name('change-country');
            Route::get('{tag?}', 'ProductController@index')->name('index');
            Route::post('export', 'ProductController@export')->name('export');
        });

        Route::group([
            'prefix' => 'ota',
            'as' => 'admin:ota.',
            'namespace' => 'Ota',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::prefix('list')->group(function () {
                Route::get('', 'OtaController@index')->name('index');
                Route::get('data', 'OtaController@loadData')->name('data');
                Route::get('add', 'OtaController@add')->name('add');
                Route::post('add', 'OtaController@store')->name('store');
                Route::get('edit/{id}', 'OtaController@edit')->name('edit');
                Route::post('edit/{id}', 'OtaController@update')->name('update');
                Route::post('delete', 'OtaController@delete')->name('delete');
            });

            Route::prefix('product')->group(function () {
                Route::get('', 'ProductOtaController@index')->name('list.index');
                Route::get('data', 'ProductOtaController@loadData')->name('list.data');
                Route::get('add', 'ProductOtaController@add')->name('list.add');
                Route::post('add', 'ProductOtaController@store')->name('list.store');
                Route::get('edit/{id}', 'ProductOtaController@edit')->name('list.edit');
                Route::post('edit/{id}', 'ProductOtaController@update')->name('list.update');
                Route::post('delete', 'ProductOtaController@delete')->name('list.delete');
                Route::get('update-status', 'ProductOtaController@updateStatus')->name('list.update-status');
            });

        });

        Route::group([
            'prefix' => 'kyc',
            'as' => 'admin:kyc.',
            'namespace' => 'Kyc',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::get('', 'KycController@index')->name('index');
            Route::get('data', 'KycController@loadData')->name('data');
            Route::get('detail', 'KycController@getData')->name('detail');
            Route::get('download', 'KycController@download')->name('download');
            Route::post('add', 'KycController@store')->name('save');
            Route::post('approve', 'KycController@approve')->name('approve');
            Route::post('reject', 'KycController@reject')->name('reject');
            Route::post('update', 'KycController@update')->name('update');
            Route::post('delete', 'KycController@delete')->name('delete');
        });
        Route::group([
            'prefix' => 'setting',
            'as' => 'admin:setting.',
            'namespace' => 'Setting',
            'middleware' => 'admin:customer-support|business-development'
        ], function () {
            Route::group(['prefix' => 'restrict-sub-domain', 'as' => 'restrict.'], function () {
                Route::get('', 'RestrictSubdomainCtrl@index')->name('index');
                Route::get('data', 'RestrictSubdomainCtrl@loadData')->name('data');
                Route::post('add', 'RestrictSubdomainCtrl@store')->name('save');
                Route::post('update', 'RestrictSubdomainCtrl@update')->name('update');
                Route::post('delete', 'RestrictSubdomainCtrl@destroy')->name('delete');
            });
        });

        Route::group([
            'as' => 'admin:setting.',
            'namespace' => 'Setting',
            'middleware' => 'admin:super-admin'
        ], function () {
            Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
                Route::get('', 'AdminController@index')->name('index');
                Route::get('data', 'AdminController@loadData')->name('data');
                Route::get('add', 'AdminController@add')->name('add');
                Route::post('add', 'AdminController@store')->name('save');
                Route::get('edit/{id}', 'AdminController@edit')->name('edit');
                Route::post('edit/{id}', 'AdminController@update')->name('update');
                Route::post('delete', 'AdminController@destroy')->name('delete');
            });
        });
        Route::group([
            'prefix' => 'seo-page',
            'as' => 'admin:seo.',
            'namespace' => 'Seo',
            'middleware' => 'admin:digital-marketing'
        ], function () {
            Route::get('', 'SeoPageCtrl@index')->name('index');
            Route::get('data', 'SeoPageCtrl@loadData')->name('data');
            Route::get('{id}/edit', 'SeoPageCtrl@edit')->name('edit');
            Route::post('{id}/edit', 'SeoPageCtrl@update')->name('update');
        });

        Route::group([
            'prefix' => 'bot',
            'as' => 'admin:bot.',
            'namespace' => 'Bot',
            'middleware' => 'admin:super-admin'
        ], function () {
            Route::get('', 'WoowaBotController@index')->name('index');
            Route::get('add', 'WoowaBotController@add')->name('add');
            Route::delete('delete', 'WoowaBotController@delete')->name('delete');
            Route::post('save', 'WoowaBotController@save')->name('save');
            Route::post('update', 'WoowaBotController@update')->name('update');
        });

        Route::group(['prefix' => 'directory', 'as' => 'admin:directory.', 'namespace' => 'Directory'], function () {
            Route::group([
                'prefix' => 'popular',
                'as' => 'popular.',
                'middleware' => 'admin:customer-support|business-development'
            ], function () {
                Route::get('city', 'PopularCityCtrl@index')->name('city.index');
                Route::post('city', 'PopularCityCtrl@saveImage')->name('city.save-image');
            });
            Route::group([
                'prefix' => 'career',
                'as' => 'career.',
                'namespace' => 'Career',
                'middleware' => 'admin:hrd'
            ], function () {
                Route::get('', 'CareerCtrl@index')->name('index');
                Route::get('data', 'CareerCtrl@loadData')->name('data');
                Route::get('create', 'CareerCtrl@create')->name('create');
                Route::post('add', 'CareerCtrl@save')->name('save');
                Route::post('delete', 'CareerCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'CareerCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'CareerCtrl@update')->name('update');
            });
            Route::group([
                'prefix' => 'job-applicant',
                'as' => 'job-applicant.',
                'namespace' => 'Career',
                'middleware' => 'admin:hrd'
            ], function () {
                Route::get('', 'ApplicantCtrl@index')->name('index');
                Route::get('data', 'ApplicantCtrl@loadData')->name('data');
                Route::get('create', 'ApplicantCtrl@create')->name('create');
                Route::post('add', 'ApplicantCtrl@save')->name('save');
                Route::post('delete', 'ApplicantCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'ApplicantCtrl@edit')->name('edit');
                Route::get('download/{id}', 'ApplicantCtrl@download')->name('download');
                Route::post('edit/{id}', 'ApplicantCtrl@update')->name('update');
                Route::post('read', 'ApplicantCtrl@markAsRead')->name('read');
            });
            Route::group([
                'prefix' => 'blog/post',
                'as' => 'post.',
                'namespace' => 'Blog',
                'middleware' => 'admin:content-writer'
            ], function () {
                Route::get('', 'PostCtrl@index')->name('index');
                Route::get('data', 'PostCtrl@loadData')->name('data');
                Route::get('create', 'PostCtrl@create')->name('create');
                Route::post('add', 'PostCtrl@save')->name('save');
                Route::post('delete', 'PostCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'PostCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'PostCtrl@update')->name('update');
                Route::post('active', 'PostCtrl@active')->name('active');
                Route::post('nonactive', 'PostCtrl@nonactive')->name('nonactive');
            });

            Route::group([
                'prefix' => 'blog/category',
                'as' => 'category.',
                'namespace' => 'Blog',
                'middleware' => 'admin:content-writer'
            ], function () {
                Route::get('', 'CategoryCtrl@index')->name('index');
                Route::get('data', 'CategoryCtrl@loadData')->name('data');
                Route::post('add', 'CategoryCtrl@store')->name('save');
                Route::post('update', 'CategoryCtrl@update')->name('update');
                Route::post('delete', 'CategoryCtrl@destroy')->name('delete');

            });

            Route::group([
                'prefix' => 'blog/tags',
                'as' => 'tag.',
                'namespace' => 'Blog',
                'middleware' => 'admin:content-writer'
            ], function () {
                Route::get('', 'TagCtrl@index')->name('index');
                Route::get('data', 'TagCtrl@loadData')->name('data');
                Route::post('add', 'TagCtrl@store')->name('save');
                Route::post('update', 'TagCtrl@update')->name('update');
                Route::post('delete', 'TagCtrl@destroy')->name('delete');
            });

            Route::group([
                'prefix' => 'press-releases',
                'as' => 'press-releases.',
                'middleware' => 'admin:content-writer'
            ], function () {
                Route::get('', 'PressReleasesCtrl@index')->name('index');
                Route::get('data', 'PressReleasesCtrl@loadData')->name('data');
                Route::get('create', 'PressReleasesCtrl@create')->name('create');
                Route::post('add', 'PressReleasesCtrl@save')->name('save');
                Route::post('delete', 'PressReleasesCtrl@delete')->name('delete');
                Route::get('edit/{id}', 'PressReleasesCtrl@edit')->name('edit');
                Route::post('edit/{id}', 'PressReleasesCtrl@update')->name('update');
            });
        });


        Route::group(['prefix' => 'test', 'as' => 'admin:test.', 'middleware' => 'superadmin'], function () {
            Route::get('fix-language', function () {
                $products = \App\Models\Product::all();
                $indo = \App\Models\Language::firstOrCreate(['language_name' => 'Indonesian']);
                $eng = \App\Models\Language::firstOrCreate(['language_name' => 'English']);

                foreach ($products as $product) {
                    switch ($product->guide_language) {
                        case 'Bahasa Indonesia':
                            $product->languages()->sync($indo);
                            break;
                        case 'English':
                            $product->languages()->sync($eng);
                            break;
                        case 'Bilingual':
                            $product->languages()->sync([$indo->id_language, $eng->id_language]);
                            break;
                        default:
                            $product->languages()->sync($indo);

                    }
                }

            });

            Route::get('fix-admin', function () {
                \App\Models\Admin::where('id', 2)->update([
                    'role_id' => 2,
                    'admin_name' => 'Admin'
                ]);
                \App\Models\Admin::where('id', 1)->update([
                    'password' => '$2y$10$OboKCbP0/VcI03jsDjOBuOYVetQziqioaom5suCdoj0214YFBR4f2'
                ]);
            });

            Route::get('download', function () {
                return view('back-office.export-custom');
            });
            Route::post('download', function (\Illuminate\Http\Request $request) {
                $headers = explode(',', $request->input('headers'));
                $raw = $request->input('raw');

                return (new \App\Exports\Custom\CustomReport($headers, $raw))->download('kayiz.xls');
            });

            Route::get('reimbursement', function (\Illuminate\Http\Request $request) {
                $order = \App\Models\Order::whereHas('voucher', function ($voucher) {
                    $voucher->where('by_gomodo', 1);
                })->where('status', 1);
                if (checkRequestExists($request, 'start')) {
                    $order = $order->where('created_at', '>=', \Carbon\Carbon::parse($request->get('start'))->toDateTimeString());
                }
                $orderReimbursementData = $order->where('reimbursement', 1)->get();
                $count = 0;
                $data = [];
                $data[$count]['title'] = ' Has been Reimbursed';
                $data[$count]['headers'] = [
                    'Date',
                    'Company',
                    'Nominal Voucher',
                ];
                $urut = 0;
                foreach ($orderReimbursementData as $builder) {
                    $data[$count]['data'][$urut]['order_date'] = $builder->created_at;
                    $data[$count]['data'][$urut]['company'] = $builder->company->domain_memoria;
                    $data[$count]['data'][$urut]['voucher_amount'] = $builder->voucher_amount;
                    $urut++;
                }
                return (new App\Exports\Custom\ReportInvestor(toObject($data)))->download('Report ' . \Carbon\Carbon::now()->toDateTimeString() . '.xls');
            });

            Route::get('not reimbursement', function (\Illuminate\Http\Request $request) {
                $order = \App\Models\Order::whereHas('voucher', function ($voucher) {
                    $voucher->where('by_gomodo', 1);
                })->where('status', 1);
                if (checkRequestExists($request, 'start')) {
                    $order = $order->where('created_at', '>=', \Carbon\Carbon::parse($request->get('start'))->toDateTimeString());
                }
                $orderNotYetReimbursementData = $order->where('reimbursement', 0)->get();
                $count = 0;
                $data = [];
                $data[$count]['title'] = ' Has been Reimbursed';
                $data[$count]['headers'] = [
                    'Date',
                    'Company',
                    'Nominal Voucher',
                ];
                $urut = 0;
                foreach ($orderNotYetReimbursementData as $builder) {
                    $data[$count]['data'][$urut]['order_date'] = $builder->created_at;
                    $data[$count]['data'][$urut]['company'] = $builder->company->domain_memoria;
                    $data[$count]['data'][$urut]['voucher_amount'] = $builder->voucher_amount;
                    $urut++;
                }
                return (new App\Exports\Custom\ReportInvestor(toObject($data)))->download('Report ' . \Carbon\Carbon::now()->toDateTimeString() . '.xls');
            });

            Route::get('/gmvprovider', function () {
                return (new \App\Exports\Custom\ProviderGmv())->download('Report ' . \Carbon\Carbon::now()->toDateTimeString() . '.xls');
            });
            Route::get('/exportpremium', function () {
                return (new \App\Exports\Custom\PremiumReport())->download('Report ' . \Carbon\Carbon::now()->toDateTimeString() . '.xls');
            });

        });
    });

    // Coming soon hendy
    Route::get('earning', function () {
        return view('new-backoffice.earning.index');
    });
    Route::get('/notification', function () {
        return view('new-backoffice.notification.index');
    });
    Route::get('/messages', function () {
        return view('new-backoffice.message.index');
    });
    Route::get('/broadcast', function () {
        return view('new-backoffice.broadcast.index');
    });
    Route::get('/faq', function () {
        return view('new-backoffice.partial.faq');
    });
    Route::get('/terms-and-condition', function () {
        return view('new-backoffice.partial.terms_condition');
    });
    Route::get('/privacy-policy', function () {
        return view('new-backoffice.partial.privacy_policy');
    });
    Route::get('/product-tag/edit', function () {
        return view('new-backoffice.list.edit_tag_product');
    });
    Route::get('/product/edit', function () {
        return view('new-backoffice.list.edit_product');
    });

    Route::get('/analytics', function () {
        $msg = [];
        $topBrowser = Analytics::fetchTopBrowsers(Period::days(7));
        $msg[] = "**TOP BROWSER**";
        $msg[] = "```";
        foreach ($topBrowser as $view) {
            $msg[] = "---------------------------------";
            $msg[] = "Browser     : " . $view['browser'];
            $msg[] = "Sessions    : " . $view['sessions'];
        }
        $msg[] = "```";
        $msg[] = "\n";
        $msg[] = "\n";


        $headers = array(
            'Content-Type:application/json'
        );
        $method = "POST";
        $data['content'] = sprintf('%s', implode("\n", $msg));
        $data = json_encode($data);
        $url = 'https://discordapp.com/api/webhooks/710765644332662874/ft_xkUjeUJRnm83mYbuLu2g1P2GPSyp4fbKLXiyurRlA7bVwShsBOdx6YVrI73tzezs1';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        dd($result);
    });

    Route::prefix('hhbk')->middleware('admin:super-admin|business-development')->group(function (){
        Route::get('','Hhbk\HhbkController@index')->name('admin:hhbk.index');
        Route::get('data','Hhbk\HhbkController@getData')->name('admin:hhbk.data');
        Route::post('import','Hhbk\HhbkController@importExcell')->name('admin:hhbk.import');
        Route::get('add','Hhbk\HhbkController@add')->name('admin:hhbk.add');
        Route::post('add','Hhbk\HhbkController@save')->name('admin:hhbk.save');
        Route::get('edit','Hhbk\HhbkController@edit')->name('admin:hhbk.edit');
        Route::post('edit','Hhbk\HhbkController@update')->name('admin:hhbk.update');
        Route::post('delete','Hhbk\HhbkController@delete')->name('admin:hhbk.delete');
    });
    Route::prefix('hhbk-distribution')->middleware('admin:super-admin|business-development')->group(function (){
        Route::get('','Outsider\OutsideController@index')->name('admin:hhbk-distribution.index');
        Route::get('data','Outsider\OutsideController@getData')->name('admin:hhbk-distribution.data');
        Route::post('import','Outsider\OutsideController@importExcell')->name('admin:hhbk-distribution.import');
        Route::get('add','Outsider\OutsideController@add')->name('admin:hhbk-distribution.add');
        Route::post('add','Outsider\OutsideController@save')->name('admin:hhbk-distribution.save');
        Route::get('edit','Outsider\OutsideController@edit')->name('admin:hhbk-distribution.edit');
        Route::post('edit','Outsider\OutsideController@update')->name('admin:hhbk-distribution.update');
        Route::post('delete','Outsider\OutsideController@delete')->name('admin:hhbk-distribution.delete');
    });

    Route::prefix('insurance-request')->middleware('admin:super-admin|business-development')->group(function (){
        Route::get('','Insurance\InsuranceRequestController@index')->name('admin:insurance-request.index');
        Route::get('data','Insurance\InsuranceRequestController@getData')->name('admin:insurance-request.data');
    });
});

