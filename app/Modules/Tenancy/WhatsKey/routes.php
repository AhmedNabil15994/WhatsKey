<?php 

/*----------------------------------------------------------
WhatsKey
----------------------------------------------------------*/
Route::group(['prefix' => '/services'] , function (){
	$controller = \App\Http\Controllers\WhatskeyControllers::class;

	Route::group(['prefix' => '/webhooks'] ,function() use ($controller){
		Route::webhooks('/messages-webhook','default');
		Route::webhooks('/acks-webhook','acks');
		Route::webhooks('/chats-webhook','chats');
	});
    
	
});
