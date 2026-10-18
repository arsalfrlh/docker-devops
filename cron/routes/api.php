<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\OrderApiController;
use App\Http\Controllers\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/product',ProductApiController::class);
Route::post('/login',[AuthApiController::class,'login']);
Route::post('/register',[AuthApiController::class,'register']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('/order',OrderApiController::class);
});