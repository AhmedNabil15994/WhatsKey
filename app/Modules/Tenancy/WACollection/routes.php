<?php 

/*----------------------------------------------------------
Collections
----------------------------------------------------------*/
$controller = \App\Http\Controllers\WACollectionControllers::class;
Route::group(['prefix' => '/collections'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
});
