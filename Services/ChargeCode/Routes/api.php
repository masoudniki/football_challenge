<?php

use Illuminate\Support\Facades\Route;
use Services\Wallet\Http\Controllers\WalletController;

Route::group(["prefix"=>"v1"],function () {
    Route::group(["prefix" => "wallet"], function () {
        Route::get("/credit/{user:username}", [WalletController::class, "credit"]);
        Route::get("/transactions/{user:username}", [WalletController::class, "transactions"]);
        Route::get("/transactions/{user:username}/{transaction}", [WalletController::class, "transaction"]);
        Route::post("/applyChargeCode", [WalletController::class, "applyChargeCode"]);
    });
});
