<?php

/*----------------------------------------------------------
Pages
----------------------------------------------------------*/
Route::group(['prefix' => '/pages'] , function () {
    Route::get('/', 'PageControllers@index');
    Route::get('/add', 'PageControllers@add');
    Route::get('/edit/{id}', 'PageControllers@edit');
    Route::post('/update/{id}', 'PageControllers@update');
    Route::post('/fastEdit', 'PageControllers@fastEdit');
	Route::post('/create', 'PageControllers@create');
    Route::get('/delete/{id}', 'PageControllers@delete');
    Route::group(['prefix' => '/notifications'] , function () {
        Route::get('/', 'PageControllers@notifications');
        Route::post('/create', 'PageControllers@createNotification');
    });
});