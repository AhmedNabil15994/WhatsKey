<?php

/*----------------------------------------------------------
Membership Features
----------------------------------------------------------*/
Route::group(['prefix' => '/features'] , function () {
    Route::get('/', 'FeatureControllers@index');
    Route::get('/add', 'FeatureControllers@add');
    Route::get('/edit/{id}', 'FeatureControllers@edit');
    Route::post('/update/{id}', 'FeatureControllers@update');
    Route::post('/fastEdit', 'FeatureControllers@fastEdit');
	Route::post('/create', 'FeatureControllers@create');
    Route::get('/delete/{id}', 'FeatureControllers@delete');
});