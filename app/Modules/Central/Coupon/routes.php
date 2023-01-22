<?php

/*----------------------------------------------------------
Coupons
----------------------------------------------------------*/
Route::group(['prefix' => '/coupons'] , function () {
    Route::get('/', 'CouponControllers@index');
    Route::get('/add', 'CouponControllers@add');
    Route::get('/edit/{id}', 'CouponControllers@edit');
    Route::post('/update/{id}', 'CouponControllers@update');
    Route::post('/fastEdit', 'CouponControllers@fastEdit');
	Route::post('/create', 'CouponControllers@create');
    Route::get('/delete/{id}', 'CouponControllers@delete');
});