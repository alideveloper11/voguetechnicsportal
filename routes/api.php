<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuoteApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('quotes', [QuoteApiController::class, 'store']);
    });
});
