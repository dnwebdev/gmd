<?php
Route::group(['middleware' => ['language','cors','b2c']], function () {
    Route::get('/', 'Explore\ExploreCtrl@index')->name('explore.index');
    Route::get('/test', 'Explore\ExploreCtrl@index')->name('explore.test');
    Route::get('{type}/search', 'Explore\ExploreCtrl@afterSearch')->name('explore.search');
    Route::post('render', 'Explore\ExploreCtrl@searchAjax')->name('explore.render');
    Route::get('subscribe', 'Explore\NewsLetterCtrl@subscribe')->name('explore.subscribe');
    Route::get('all-destination', 'Explore\ExploreCtrl@allDestination')->name('explore.all-destination');
    Route::post('all-destination/render', 'Explore\ExploreCtrl@renderCity')->name('explore.all-destination.render');
    Route::get('/help', 'Explore\ExploreCtrl@help')->name('explore.help');
    Route::get('/policy', 'Explore\ExploreCtrl@policy')->name('explore.policy');
    Route::get('/about_us', 'Explore\ExploreCtrl@about_us')->name('explore.about_us');
    Route::get('/term-condition', 'Explore\ExploreCtrl@termCondition')->name('explore.term-condition');

    Route::get('/careers', 'Explore\CareerCtrl@index')->name('explore.careers.index');
    Route::post('/careers/render', 'Explore\CareerCtrl@ajaxLoadMore')->name('explore.careers.render');
    Route::get('/career/{id}', 'Explore\CareerCtrl@detail')->name('explore.careers.detail');
    Route::get('/career/{id}/apply', 'Explore\CareerCtrl@requestForm')->name('explore.careers.request');
    Route::post('/career/{id}/apply', 'Explore\CareerCtrl@apply')->name('explore.careers.apply');

    Route::get('/blog', 'Explore\BlogController@index')->name('explore.blog.index');
    Route::get('/blog/{slug}', 'Explore\BlogController@detailBlog')->name('blog.detail');
    Route::get('/blog/{type}/search', 'Explore\BlogController@afterSearch')->name('blog.search');
    Route::post('/blog/render', 'Explore\BlogController@searchAjax')->name('blog.render');

    Route::get('/pres-releases', 'Explore\PressReleasesCtrl@index')->name('pres_releases.index');
    Route::get('/pres-releases/{slug}', 'Explore\PressReleasesCtrl@detailPres')->name('pres_releases.detail');
    Route::get('/press-releases/{type}/search', 'Explore\PressReleasesCtrl@afterSearch')->name('pres_releases.search');
    Route::post('/press-releases/render', 'Explore\PressReleasesCtrl@searchAjax')->name('pres_releases.render');

//    Route::get('test', function (){
//       $tag = \App\Models\TagProduct::with(['products'=>function($p){
//           $p->select('id_product','publish');
//       }])->whereHas('products', function ($product){
//           $product->where('product_name','like','%jalan%')
////               ->where('publish',1)
//           ;
//       })->get();
//       dd($tag->toArray());
//    });
});
