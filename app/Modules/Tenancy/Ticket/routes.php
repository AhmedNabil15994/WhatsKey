<?php 

/*----------------------------------------------------------
Ticket
----------------------------------------------------------*/
Route::group(['prefix' => '/tickets'] , function () {
    $controller = \App\Http\Controllers\TicketControllers::class;
    Route::get('/', [$controller,'index']);
    Route::get('/add', [$controller,'add']);
    Route::get('/view/{id}', [$controller,'view']);
	Route::post('/create', [$controller,'create']);
    Route::get('/delete/{id}', [$controller,'delete']);
    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/
    Route::post('/add/uploadImage', [$controller,'uploadImage']);
    /*----------------------------------------------------------
    Comments
    ----------------------------------------------------------*/
    Route::post('/view/{id}/addComment', [$controller,'addComment']);
    Route::get('/view/{id}/removeComment/{commentId}', [$controller,'removeComment']);
    Route::post('/view/{id}/uploadCommentFile', [$controller,'uploadImage']);

});