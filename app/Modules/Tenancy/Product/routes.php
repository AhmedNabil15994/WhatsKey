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
    Route::post('/fastEdit', [$controller,'fastEdit']);
    Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
    Route::get('/view/{id}', [$controller,'view']);

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage/{type}', [$controller,'uploadImage']);
    Route::post('/edit/editImage/{type}', [$controller,'uploadImage']);
    Route::post('/edit/{id}/deleteImage', [$controller,'deleteImage']);
});