<?php
/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    $authController = App\Http\Controllers\AuthControllers::class;

    Route::get('/', function(){return redirect('/login');});
    Route::get('/login', [$authController,'login'])->name('login');    
    Route::get('/loginByCode', [$authController,'loginByCode'])->name('loginByCode');
    Route::post('/login', [$authController,'doLogin'])->name('doLogin');
    Route::post('/checkByCode', [$authController,'checkByCode']);
    Route::get('/logout', [$authController,'logout']);

    Route::get('/resetPassword', [$authController,'getResetPassword'])->name('getResetPassword');
    Route::post('/resetPassword', [$authController,'resetPassword'])->name('resetPassword');
    Route::get('/changePassword', [$authController,'changePassword']);
    Route::post('/checkAvailabilityCode', [$authController,'checkResetPassword']);
    Route::post('/completeReset', [$authController,'completeReset']);
    Route::get('/impersonate/{token}',[App\Http\Controllers\ImpersonatesController::class, 'index'])->name('impersonate');
});