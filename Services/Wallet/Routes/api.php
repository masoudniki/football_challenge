<?php

use Illuminate\Support\Facades\Route;
use Services\ChargeCode\Http\Controllers\ChargeCodeController;


Route::group(["prefix"=>"v1"],function () {
    Route::group(["prefix" => "charge-code"], function () {
        Route::get("/reports", [ChargeCodeController::class, "reports"]);
        Route::get("/reports/{chargeCode:code}", [ChargeCodeController::class, "report"]);
    });
});
