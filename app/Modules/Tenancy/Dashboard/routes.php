<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardControllers::class,'Dashboard'])->name('userDash');
    Route::get('/completeJob', [App\Http\Controllers\DashboardControllers::class,'completeJob']);
    Route::get('/paymentError', [App\Http\Controllers\DashboardControllers::class,'paymentError']);
    Route::get('/QR', [App\Http\Controllers\DashboardControllers::class,'qrIndex']);
    Route::post('/QR/updateName', [App\Http\Controllers\DashboardControllers::class,'updateName']);
    Route::post('/QR/getQR', [App\Http\Controllers\DashboardControllers::class,'getQR']);
    Route::get('/changeLang', [App\Http\Controllers\DashboardControllers::class,'changeLang']);
    
    Route::group(['prefix' => '/helpCenter'] , function () {
        Route::get('/', [App\Http\Controllers\DashboardControllers::class,'helpCenter']);
        Route::get('/changeLogs', [App\Http\Controllers\DashboardControllers::class,'changeLogs']);
        Route::post('/changeLogs/addRate', [App\Http\Controllers\DashboardControllers::class,'addRate']);   
        Route::get('/faq', [App\Http\Controllers\DashboardControllers::class,'faqs']);
    });
});