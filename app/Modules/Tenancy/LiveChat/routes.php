<?php 

/*----------------------------------------------------------
LiveChat
----------------------------------------------------------*/
$controller = \App\Http\Controllers\LiveChatControllers::class;
Route::group(['prefix' => '/livechat'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
    Route::post('/upload',[$controller,'upload']);
    Route::post('/updateContact', [$controller,'updateContact']);

    Route::get('/liveChatLogout',[$controller,'liveChatLogout']);
});
