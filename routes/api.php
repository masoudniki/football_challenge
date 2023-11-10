<?php

use App\Http\Controllers\WalletController;
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
            Route::post("/applyChargeCode",[WalletController::class,"applyChargeCode"]);
        });
});
