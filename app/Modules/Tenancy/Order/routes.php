<?php

/*----------------------------------------------------------
Orders
----------------------------------------------------------*/
Route::group(['prefix' => '/orders'] , function () {
    $controller = \App\Http\Controllers\OrderControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/view/{id}', [$controller,'view']);
});