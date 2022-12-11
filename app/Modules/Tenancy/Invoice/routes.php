<?php

/*----------------------------------------------------------
Invoices
----------------------------------------------------------*/
Route::group(['prefix' => '/invoices'] , function () {
    $controller = \App\Http\Controllers\TenantInvoiceControllers::class;

    Route::get('/', [$controller,'index']);
    Route::get('/view/{id}', [$controller,'view']);
    Route::get('/view/{id}/checkout', [$controller,'checkout']);
    Route::get('/{id}/downloadPDF', [$controller,'downloadPDF']);
    Route::post('/view/{id}/checkout', [$controller,'postCheckout']);
});