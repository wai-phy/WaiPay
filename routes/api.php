<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::namespace('Api')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/profile',[PageController::class,'profile']);
        Route::post('/logout',[AuthController::class,'logout']);

        Route::get('/transaction',[PageController::class,'transaction']);
        Route::get('/transaction/{trx_id}',[PageController::class,'transactionDetail']);

        Route::get('/notification',[PageController::class,'notification']);
        Route::get('/notification/{id}',[PageController::class,'notificationDetail']);

    });
});