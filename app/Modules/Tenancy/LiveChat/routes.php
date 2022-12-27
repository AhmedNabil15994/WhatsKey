<?php 

/*----------------------------------------------------------
LiveChat
----------------------------------------------------------*/
$controller = \App\Http\Controllers\LiveChatControllers::class;
Route::group(['prefix' => '/livechat'] , function () use ($controller) {
    Route::get('/', [$controller,'index']);
    Route::post('/upload',[$controller,'upload']);
    Route::post('/updateContact', [$controller,'updateContact']);




    Route::get('/dialogs', [$controller,'dialogs']);
    Route::post('/pinChat', [$controller,'pinChat']);
    Route::post('/unpinChat', [$controller,'unpinChat']);
    Route::post('/readChat', [$controller,'readChat']);
    Route::post('/unreadChat', [$controller,'unreadChat']);

    Route::get('/messages', [$controller,'messages']);
    Route::get('/messages/repeatHoook', [$controller,'repeatHoook']);
    Route::post('/messages/deleteMessage', [$controller,'deleteMessage']);
    Route::post('/sendMessage', [$controller,'sendMessage']);
    
    Route::get('/labels', [$controller,'labels']);
    Route::post('/labelChat', [$controller,'labelChat']);
    Route::post('/unlabelChat', [$controller,'unlabelChat']);

    Route::get('/contact', [$controller,'contact']);

    Route::get('/quickReplies', [$controller,'quickReplies']);

    Route::get('/moderators', [$controller,'moderators']);
    Route::post('/assignMod', [$controller,'assignMod']);
    Route::post('/removeMod', [$controller,'removeMod']);
    Route::get('/liveChatLogout',[$controller,'liveChatLogout']);
});
