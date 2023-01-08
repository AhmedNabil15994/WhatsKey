<?php

Route::group(['prefix' => '/engine'] , function () {
    /*----------------------------------------------------------
    Instances
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/instances'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Messages
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/messages'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Dialogs
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/dialogs'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Webhooks
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/webhooks'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Queues
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/queues'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Ban Settings
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/ban'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Testing
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/testing'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });


    /*----------------------------------------------------------
    Users
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/users'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });

    /*----------------------------------------------------------
    Products
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/products'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/{status}', [$controller,'index']);
    });

    /*----------------------------------------------------------
    Channels
    ----------------------------------------------------------*/
    Route::group(['prefix' => '/channels'] , function () {
        $controller = App\Http\Controllers\EngineControllers::class;
        Route::post('/createChannel', [$controller,'createChannel']);
        Route::post('/deleteChannel', [$controller,'deleteChannel']);
        Route::post('/transferDays', [$controller,'transferDays']);
        Route::post('/', [$controller,'channels']);
    });
});
