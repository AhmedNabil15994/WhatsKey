<?php 

/*----------------------------------------------------------
Users
----------------------------------------------------------*/
Route::group(['prefix' => '/profile'] , function () {
    $controller = \App\Http\Controllers\ProfileControllers::class;    
    Route::get('/personalInfo', [$controller,'personalInfo']);
    Route::post('/updatePersonalInfo', [$controller,'updatePersonalInfo']);
    Route::post('/postChangePassword', [$controller,'postChangePassword']);
    Route::post('/postPaymentInfo', [$controller,'postPaymentInfo']);
    Route::post('/personalInfo/editImage', [$controller,'uploadImage']);
    Route::post('/personalInfo/deleteImage', [$controller,'deleteImage']);

    Route::get('/extraQuotas', [$controller,'extraQuotas']);
    Route::get('/extraQuotas/{id}', [$controller,'postExtraQuotas']);
    Route::get('/addons', [$controller,'addons']);
    Route::post('/addons/{id}', [$controller,'postAddons']);

    Route::group(['prefix' => '/subscription'] , function () {
        $controller2 = \App\Http\Controllers\WAAccountController::class;
        Route::get('/', [$controller2,'subscription']);
        Route::get('/unBlock/{chatId}', [$controller2,'unBlock']);
        Route::get('/screenshot', [$controller2,'screenshot']);
        Route::get('/syncAll', [$controller2,'syncAll']);
        Route::get('/closeConn', [$controller2,'closeConn']);
        Route::get('/read/{status}', [$controller2,'read']);
        Route::get('/syncDialogs', [$controller2,'syncDialogs']);
        Route::get('/syncContacts', [$controller2,'syncContacts']);
        Route::get('/syncLabels', [$controller2,'syncLabels']);
        Route::get('/restoreAccountSettings', [$controller2,'restoreAccountSettings']);
        Route::get('/clearMessagesQueue', [$controller2,'clearMessagesQueue']);
    }); 
    
    Route::get('/apiSetting', [\App\Http\Controllers\ApiSettingController::class,'apiSetting']);
    Route::get('/apiGuide', [\App\Http\Controllers\ApiSettingController::class,'apiGuide']);

    Route::get('/webhookSetting', [\App\Http\Controllers\ApiSettingController::class,'webhookSetting']);
    Route::post('/postWebhookSetting', [\App\Http\Controllers\ApiSettingController::class,'postWebhookSetting']);

});