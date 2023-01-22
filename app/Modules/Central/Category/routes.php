<?php

/*----------------------------------------------------------
Categories
----------------------------------------------------------*/
Route::group(['prefix' => '/categories'] , function () {
    Route::get('/', 'CentralCategoryControllers@index');
    Route::get('/add', 'CentralCategoryControllers@add');
    Route::get('/edit/{id}', 'CentralCategoryControllers@edit');
    Route::post('/update/{id}', 'CentralCategoryControllers@update');
    Route::post('/fastEdit', 'CentralCategoryControllers@fastEdit');
    Route::post('/create', 'CentralCategoryControllers@create');
    Route::get('/delete/{id}', 'CentralCategoryControllers@delete');
});