<?php

    Route::get('/impersonate/{token}',[App\Http\Controllers\ImpersonatesController::class, 'index'])->name('impersonate');
    Route::get('/',function(){
    	dd('hello tenant landing page');
    });

    Route::get('/logout',function(){
        \Session::flush();
        session()->flush();
        return redirect()->to(config('app.BASE_URL').'/logout');
    });