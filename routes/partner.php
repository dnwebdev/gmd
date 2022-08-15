<?php
Route::group(['middleware' => 'json', 'namespace' => 'Api'], function () {
    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');
        Route::post('register-otp', 'Auth\RegisterController@OtpRegister');
    });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'User\ProfileController@detail');
        Route::get('myproduct/list', 'Product\ProductController@myProduct');
        Route::post('myproduct/create','Product\ProductController@createProduct');
        Route::put('myproduct/edit/{id}','Product\ProductController@editPorduct');
    });

    Route::group([], function (){
        Route::get('product/list','Product\ProductController@listProduct');
        Route::get('product/detail','Product\ProductController@detailProduct');
    });
});
