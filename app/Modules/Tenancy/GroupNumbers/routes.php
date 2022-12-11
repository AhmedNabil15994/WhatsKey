<?php 

/*----------------------------------------------------------
Numbers Groups
----------------------------------------------------------*/
$controller = \App\Http\Controllers\GroupNumbersControllers::class;
Route::group(['prefix' => '/groupNumbers'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::post('/fastEdit', [$controller,'fastEdit']);
	Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
    Route::get('/reports', [$controller,'report']);

    Route::group(['prefix' => '/addGroupNumbers'] ,function() use ($controller){
        Route::get('/', [$controller,'addGroupNumbers']);
        Route::post('/create', [$controller,'postAddGroupNumbers']);
        Route::post('/checkFile', [$controller,'checkFile']);
    });
});

