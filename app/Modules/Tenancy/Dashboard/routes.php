<?php

/*----------------------------------------------------------
Dashboard
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardControllers::class,'Dashboard'])->name('userDash');
    Route::get('/completeJob', [App\Http\Controllers\DashboardControllers::class,'completeJob']);
    Route::get('/QR', [App\Http\Controllers\DashboardControllers::class,'qrIndex']);
    Route::post('/QR/updateName', [App\Http\Controllers\DashboardControllers::class,'updateName']);
    Route::post('/QR/getQR', [App\Http\Controllers\DashboardControllers::class,'getQR']);
    Route::get('/changeLang', [App\Http\Controllers\DashboardControllers::class,'changeLang']);
    
    Route::group(['prefix' => '/helpCenter'] , function () {
        Route::get('/', [App\Http\Controllers\DashboardControllers::class,'helpCenter']);
        Route::get('/changeLogs', [App\Http\Controllers\DashboardControllers::class,'changeLogs']);
        Route::post('/changeLogs/addRate', [App\Http\Controllers\DashboardControllers::class,'addRate']);   
        Route::get('/faq', [App\Http\Controllers\DashboardControllers::class,'faqs']);
    });
});

/*----------------------------------------------------------
Subscription
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    // Route::get('/packages', [App\Http\Controllers\SubscriptionControllers::class,'packages']);
    // Route::get('/sync', [App\Http\Controllers\SubscriptionControllers::class,'sync']);
    // Route::post('/sync', [App\Http\Controllers\SubscriptionControllers::class,'postSync']);
    // Route::get('/updateSubscription', [App\Http\Controllers\SubscriptionControllers::class,'updateSubscription']);
    // Route::post('/updateSubscription', [App\Http\Controllers\SubscriptionControllers::class,'postUpdateSubscription'])->name('postCheckout');
    // Route::get('/checkout', [App\Http\Controllers\SubscriptionControllers::class,'checkout'])->name('checkout');
    // Route::get('/getCities', [App\Http\Controllers\SubscriptionControllers::class,'getCities'])->name('getCities');
    // Route::post('/checkout', [App\Http\Controllers\SubscriptionControllers::class,'postCheckout']);
    // Route::post('/checkout/bankTransfer', [App\Http\Controllers\SubscriptionControllers::class,'bankTransfer']);
    // Route::post('/coupon', [App\Http\Controllers\SubscriptionControllers::class,'addCoupon']);
    // Route::get('/postBundle/{id}', [App\Http\Controllers\SubscriptionControllers::class,'postBundle']);
    // Route::post('/completeJob', [App\Http\Controllers\SubscriptionControllers::class,'completeJob']);
    // Route::post('/completeOrder', [App\Http\Controllers\SubscriptionControllers::class,'completeOrder']);
    // Route::get('/updateAddonStatus/{addon_id}/{status}', [App\Http\Controllers\SubscriptionControllers::class,'updateAddonStatus']);
    // Route::get('/updateQuotaStatus/{extra_quota_id}/{status}', [App\Http\Controllers\SubscriptionControllers::class,'updateQuotaStatus']);
    Route::get('/paymentError', [App\Http\Controllers\SubscriptionControllers::class,'paymentError']);

});