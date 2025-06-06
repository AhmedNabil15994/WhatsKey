<?php 

/*----------------------------------------------------------
User Storage
----------------------------------------------------------*/
Route::group(['prefix' => '/storage'] , function () {
    $controller = \App\Http\Controllers\UserStorageControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/bots', [$controller,'bots']);
    Route::get('/groupMessages', [$controller,'groupMsgs']);
    Route::get('/chats', [$controller,'chats']);
    Route::get('/chats/{id}/removeFile', [$controller,'removeChatFile']);
    Route::get('/{type}/{id}', [$controller,'getByTypeAndId']);
    Route::get('/{type}/{id}/remove', [$controller,'removeByTypeAndId']);
});