<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeCode extends Model
{
    use HasFactory;
    protected $fillable=[
        "code",
        "amount",
        "usage_limit",
        "usage_count"
    ];
    function user(){
        return $this->belongsTo(User::class);
    }
}
