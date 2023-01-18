<?php 

/*----------------------------------------------------------
Profile
----------------------------------------------------------*/
Route::post('/checkout', [\App\Http\Controllers\SubscriptionControllers::class,'checkout']);
Route::group(['prefix' => '/profile'] , function () {
    $controller = \App\Http\Controllers\ProfileControllers::class;    
    Route::get('/personalInfo', [$controller,'personalInfo']);
    Route::post('/updatePersonalInfo', [$controller,'updatePersonalInfo']);
    Route::post('/postChangePassword', [$controller,'postChangePassword']);
    Route::post('/postPaymentInfo', [$controller,'postPaymentInfo']);
    Route::post('/personalInfo/editImage', [$controller,'uploadImage']);
    Route::post('/personalInfo/deleteImage', [$controller,'deleteImage']);

    Route::group(['prefix' => '/subscription'] , function () use ($controller) {
        $controller2 = \App\Http\Controllers\WAAccountController::class;
        $controller3 = \App\Http\Controllers\SubscriptionControllers::class;

        Route::get('/memberships', [$controller3,'memberships']);
        Route::get('/memberships/updateMembership', [$controller3,'updateMembership']);
        Route::post('/coupon', [$controller3,'addCoupon']);
        Route::get('/activate', [$controller3,'activate']);

        Route::get('/addons', [$controller3,'addons']);
        Route::post('/addons', [$controller3,'postAddons']);
        Route::get('/addons/disableAutoInvoice', [$controller3,'disableAddonAutoInvoice']);

        Route::get('/extraQuotas', [$controller3,'extraQuotas']);
        Route::post('/extraQuotas', [$controller3,'postExtraQuotas']);
        Route::get('/extraQuotas/disableAutoInvoice', [$controller3,'disableExtraQuotaAutoInvoice']);

        Route::get('/updateAddonStatus/{addon_id}/{status}', [$controller3,'updateAddonStatus']);
        Route::get('/updateExtraQuotaStatus/{extra_quota_id}/{status}', [$controller3,'updateExtraQuotaStatus']);
        Route::get('/transferPayment', [$controller3,'transferPayment']);

        Route::get('/', [$controller2,'subscription']);
        Route::get('/unBlock/{chatId}', [$controller2,'unBlock']);
        Route::get('/screenshot', [$controller2,'screenshot']);
        Route::get('/resyncAll', [$controller2,'resyncAll']);
        Route::get('/syncAll', [$controller2,'syncAll']);
        Route::get('/closeConn', [$controller2,'closeConn']);
        Route::get('/read/{status}', [$controller2,'read']);
        Route::get('/syncDialogs', [$controller2,'syncDialogs']);
        Route::get('/syncContacts', [$controller2,'syncContacts']);
        Route::get('/syncLabels', [$controller2,'syncLabels']);
        Route::get('/syncCollections', [$controller2,'syncCollections']);
        Route::get('/syncProducts', [$controller2,'syncProducts']);
        Route::get('/syncReplies', [$controller2,'syncReplies']);
        Route::get('/syncOrders', [$controller2,'syncOrders']);
        Route::get('/restoreAccountSettings', [$controller2,'restoreAccountSettings']);
        Route::get('/clearMessagesQueue', [$controller2,'clearMessagesQueue']);
        Route::post('/updateChannelSetting', [$controller2,'updateChannelSetting']);
        
    }); 
    
    Route::get('/apiSetting', [\App\Http\Controllers\ApiSettingController::class,'apiSetting']);
    Route::get('/apiGuide', [\App\Http\Controllers\ApiSettingController::class,'apiGuide']);

    Route::get('/webhookSetting', [\App\Http\Controllers\ApiSettingController::class,'webhookSetting']);
    Route::post('/postWebhookSetting', [\App\Http\Controllers\ApiSettingController::class,'postWebhookSetting']);

});