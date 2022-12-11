<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/

Route::group(['prefix' => '/'] , function () {
    $authController = App\Http\Controllers\CentralAuthControllers::class;
    Route::get('/login', [$authController,'login']);
    Route::post('/login', [$authController,'doLogin']);
    Route::post('/checkByCode', [$authController,'checkByCode']);
    Route::get('/logout', [$authController,'logout']);

    
    Route::get('/resetPassword', [$authController,'getResetPassword']);
    Route::post('/resetPassword', [$authController,'resetPassword']);
    Route::get('/changePassword', [$authController,'changePassword']);
    Route::post('/checkResetPassword', [$authController,'checkResetPassword']);
    Route::post('/completeReset', [$authController,'completeReset']);

    Route::get('/checkAvailability', [$authController,'checkAvailability'])->name('checkAvailability');
    Route::post('/checkAvailability', [$authController,'postCheckAvailability'])->name('postCheckAvailability');
    Route::post('/checkAvailabilityCode', [$authController,'checkAvailabilityCode'])->name('checkAvailabilityCode');
    
    Route::get('/register', [$authController,'register'])->name('register');
    Route::post('/register', [$authController,'postRegister']);

    Route::post('/changeLang', [$authController,'changeLang']);
    
    Route::get('/status', [$authController,'status'])->name('status');
    // Route::get('/appLogin', [$authController,'appLogin']);
    // Route::post('/appLogins', [$authController,'appLogins']);
});