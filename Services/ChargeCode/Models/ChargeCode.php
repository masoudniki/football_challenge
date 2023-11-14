<?php

namespace Services\ChargeCode\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Services\ChargeCode\Database\Factories\ChargeCodeFactory;

class ChargeCode extends Model
{
    use HasFactory;
    protected $fillable=[
        "code",
        "amount",
        "usage_limit",
        "usage_count"
    ];
    protected $with=["users"];
    function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,"user_charge_code","charge_code_id");
    }
    protected static function newFactory()
    {
        return ChargeCodeFactory::new();
    }

}
