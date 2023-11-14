<?php

namespace Services\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Services\Wallet\Database\Factories\TransactionFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table="transactions";
    protected $fillable=[
        "user_id",
        "amount",
        "type_id"
    ];
    protected $with=["type","information"];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function type(){
        return $this->belongsTo(TransactionType::class);
    }
    public function information(){
        return $this->hasMany(TransactionInformation::class,"transaction_id");
    }
    protected static function newFactory()
    {
        return TransactionFactory::new();
    }
}
