<?php 

/*----------------------------------------------------------
Replies
----------------------------------------------------------*/
Route::group(['prefix' => '/replies'] , function () {
    $controller = \App\Http\Controllers\RepliesControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::post('/fastEdit', [$controller,'fastEdit']);
	Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
});