<?php

/*----------------------------------------------------------
Contact Us
----------------------------------------------------------*/
Route::group(['prefix' => '/contactUs'] , function () {
    Route::get('/', 'ContactUsControllers@index');
    Route::get('/edit/{id}', 'ContactUsControllers@edit');
    Route::post('/update/{id}', 'ContactUsControllers@update');
    Route::get('/reply/{id}', 'ContactUsControllers@reply');
    Route::post('/postReply/{id}', 'ContactUsControllers@postReply');
    Route::post('/fastEdit', 'ContactUsControllers@fastEdit');
    Route::get('/delete/{id}', 'ContactUsControllers@delete');
});