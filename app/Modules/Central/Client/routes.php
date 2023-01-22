<?php 

/*----------------------------------------------------------
Clients
----------------------------------------------------------*/
Route::group(['prefix' => '/clients'] , function () {
    $controller = \App\Http\Controllers\ClientControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
   
    Route::get('/transferDay', [$controller,'transferDay']);
    Route::get('/pushChannelSetting', [$controller,'pushChannelSetting']);
    Route::get('/setInvoices', [$controller,'setInvoices']);

    Route::get('/view/{id}', [$controller,'view']);
    Route::post('/view/{id}/updatePersonalInfo', [$controller,'updatePersonalInfo']);
    Route::post('/view/{id}/updateSubscription', [$controller,'updateSubscription']);
    Route::post('/view/{id}/updateChannelSettings', [$controller,'updateChannelSettings']);
    Route::post('/view/{id}/updateSettings', [$controller,'updateSettings']);
    Route::post('/view/{id}/updatePaymentInfo', [$controller,'updatePaymentInfo']);

    Route::get('/view/{id}/delete/{type}/{type_id}', [$controller,'deleteAddon']);
    Route::get('/view/{id}/enable/{type}/{type_id}', [$controller,'enableAddon']);
    Route::get('/view/{id}/disable/{type}/{type_id}', [$controller,'disableAddon']);
    Route::post('/view/{id}/updateUserAddons', [$controller,'updateUserAddons']);
    Route::post('/view/{id}/updateUserExtraQuotas', [$controller,'updateUserExtraQuotas']);

    Route::post('/view/{id}/transferDays', [$controller,'transferDays']);
    Route::post('/view/{id}/compensation', [$controller,'compensation']);
    Route::get('/invLogin/{id}', [$controller,'invLogin']);
    Route::get('/pinCodeLogin/{id}', [$controller,'pinCodeLogin']);
});