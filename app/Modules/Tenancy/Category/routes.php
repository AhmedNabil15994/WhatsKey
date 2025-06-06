<?php 

/*----------------------------------------------------------
Categories
----------------------------------------------------------*/
Route::group(['prefix' => '/categories'] , function () {
    $controller = \App\Http\Controllers\CategoryControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/syncLabels', [$controller,'syncLabels']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::post('/fastEdit', [$controller,'fastEdit']);
	Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
});