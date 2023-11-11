<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChargeCodeCollection;
use App\Http\Resources\ChargeCodeResource;
use App\Models\ChargeCode;
use Illuminate\Http\Request;

class ChargeCodeController extends Controller
{
    public function report(ChargeCode $chargeCode){
        return new ChargeCodeResource($chargeCode);
    }
    public function reports(){
        return new ChargeCodeCollection(ChargeCode::query()->without("users")->get());
    }
}
