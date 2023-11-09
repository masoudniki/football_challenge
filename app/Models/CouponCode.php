<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;
    protected $fillable=[
        "code",
        "user_id",
        "amount"
    ];
    function user(){
        return $this->belongsTo(User::class);
    }
}
