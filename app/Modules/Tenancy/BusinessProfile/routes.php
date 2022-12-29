<?php 

/*----------------------------------------------------------
Business Profile
----------------------------------------------------------*/
$controller = \App\Http\Controllers\BusinessProfileControllers::class;
Route::group(['prefix' => '/businessProfile'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
    Route::post('/update', [$controller,'update']);
    Route::post('/deleteImage', [$controller,'deleteImage']);
    Route::post('/editImage', [$controller,'uploadImage']);
});
