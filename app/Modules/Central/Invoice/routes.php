<?php

/*----------------------------------------------------------
Invoices
----------------------------------------------------------*/
Route::group(['prefix' => '/invoices'] , function () {
    Route::get('/', 'InvoiceControllers@index');
    // Route::get('/add', 'InvoiceControllers@add');
    // Route::post('/create', 'InvoiceControllers@create');
    Route::get('/view/{id}', 'InvoiceControllers@view');
    Route::get('/{id}/downloadPDF', 'InvoiceControllers@downloadPDF');
    Route::get('/delete/{id}', 'InvoiceControllers@delete');
});