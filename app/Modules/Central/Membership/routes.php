<?php

/*----------------------------------------------------------
Memberships
----------------------------------------------------------*/
Route::group(['prefix' => '/memberships'] , function () {
    Route::get('/', 'MembershipControllers@index');
    Route::get('/add', 'MembershipControllers@add');
    Route::get('/edit/{id}', 'MembershipControllers@edit');
    Route::post('/update/{id}', 'MembershipControllers@update');
    Route::post('/fastEdit', 'MembershipControllers@fastEdit');
	Route::post('/create', 'MembershipControllers@create');
    Route::get('/delete/{id}', 'MembershipControllers@delete');
});