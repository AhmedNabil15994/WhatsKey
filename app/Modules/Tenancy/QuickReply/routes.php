<?php 

/*----------------------------------------------------------
Quick Replies
----------------------------------------------------------*/
Route::group(['prefix' => '/quickReplies'] , function () {
    $controller = \App\Http\Controllers\QuickRepliesControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::get('/delete/{id}', [$controller,'delete']);
});