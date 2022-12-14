<?php 

/*----------------------------------------------------------
Template Message
----------------------------------------------------------*/
Route::group(['prefix' => '/templateMsg'] , function () {
    $controller = \App\Http\Controllers\TemplateMsgControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/edit/{id}', [$controller,'edit']);
    Route::get('/copy/{id}', [$controller,'copy']);
    Route::get('/changeStatus/{id}', [$controller,'changeStatus']);
    Route::post('/update/{id}', [$controller,'update']);
    Route::post('/fastEdit', [$controller,'fastEdit']);
	Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', [$controller,'uploadImage']);
    Route::post('/edit/editImage', [$controller,'uploadImage']);
    Route::post('/edit/{id}/deleteImage', [$controller,'deleteImage']);
});