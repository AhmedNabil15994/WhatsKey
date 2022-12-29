<?php

/*----------------------------------------------------------
Products
----------------------------------------------------------*/
Route::group(['prefix' => '/products'] , function () {
    $controller = \App\Http\Controllers\ProductControllers::class;

    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
    Route::get('/view/{id}', [$controller,'view']);
    Route::post('/view/{id}/sendProduct', [$controller,'sendProduct']);

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', [$controller,'uploadImage']);
    Route::post('/edit/editImage', [$controller,'uploadImage']);
    Route::post('/edit/{id}/deleteImage', [$controller,'deleteImage']);
});