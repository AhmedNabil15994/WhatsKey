<?php

/*----------------------------------------------------------
Departments
----------------------------------------------------------*/
Route::group(['prefix' => '/departments'] , function () {
    Route::get('/', 'DepartmentControllers@index');
    Route::get('/add', 'DepartmentControllers@add');
    Route::get('/edit/{id}', 'DepartmentControllers@edit');
    Route::post('/update/{id}', 'DepartmentControllers@update');
    Route::post('/fastEdit', 'DepartmentControllers@fastEdit');
    Route::post('/create', 'DepartmentControllers@create');
    Route::get('/delete/{id}', 'DepartmentControllers@delete');
});