<?php

/*----------------------------------------------------------
Bank Accounts
----------------------------------------------------------*/
Route::group(['prefix' => '/bankAccounts'] , function () {
    Route::get('/', 'BankAccountControllers@index');
    Route::get('/add', 'BankAccountControllers@add');
    Route::get('/edit/{id}', 'BankAccountControllers@edit');
    Route::post('/update/{id}', 'BankAccountControllers@update');
    Route::post('/fastEdit', 'BankAccountControllers@fastEdit');
    Route::post('/create', 'BankAccountControllers@create');
    Route::get('/delete/{id}', 'BankAccountControllers@delete');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'BankAccountControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'BankAccountControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'BankAccountControllers@deleteImage');
});