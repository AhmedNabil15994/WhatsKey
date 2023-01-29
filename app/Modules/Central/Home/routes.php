<?php

/*----------------------------------------------------------
Home
----------------------------------------------------------*/

Route::group(['prefix' => '/'] , function () {
    $homeController = App\Http\Controllers\HomeControllers::class;
    Route::get('/', [$homeController,'index']);
    Route::get('/faq', [$homeController,'faq']);
    Route::get('/contact', [$homeController,'contactUs']);
    Route::post('/contact', [$homeController,'postContactUs']);
    Route::get('/whoUs', [$homeController,'whoUs']);
    Route::get('/privacy', [$homeController,'privacy']);
    Route::get('/explaination', [$homeController,'explaination']);

});