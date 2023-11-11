<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChargeCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(["prefix"=>"v1"],function (){
        Route::group(["prefix"=>"wallet"],function (){
            Route::get("/credit/{user:username}",[WalletController::class,"credit"]);
            Route::get("/transactions/{user:username}",[WalletController::class,"transactions"]);
            Route::post("/applyChargeCode",[WalletController::class,"applyChargeCode"]);
        });
        Route::group(["prefix"=>"charge-code"],function (){
            Route::get("/reports",[ChargeCodeController::class,"reports"]);
            Route::get("/reports/{chargeCode:code}",[ChargeCodeController::class,"report"]);
        });
});
