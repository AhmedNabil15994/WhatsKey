<?php 

/*----------------------------------------------------------
ApiMods
----------------------------------------------------------*/
$controller = \App\Http\Controllers\ApiModsControllers::class;
Route::group(['prefix' => '/statuses'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
});
Route::group(['prefix' => '/msgsArchive'] , function () use ($controller) {
    Route::get('/', [$controller,'msgsArchive']);
});