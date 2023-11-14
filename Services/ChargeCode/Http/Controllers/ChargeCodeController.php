<?php

namespace Services\ChargeCode\Http\Controllers;

use App\Http\Controllers\Controller;
use Services\ChargeCode\Http\Resources\ChargeCodeCollection;
use Services\ChargeCode\Http\Resources\ChargeCodeResource;
use Services\ChargeCode\Models\ChargeCode;

class ChargeCodeController extends Controller
{
    public function report(ChargeCode $chargeCode){
        return new ChargeCodeResource($chargeCode);
    }
    public function reports(){
        return new ChargeCodeCollection(ChargeCode::query()->without("users")->get());
    }
}
