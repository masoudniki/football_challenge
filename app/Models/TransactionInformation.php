<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionInformation extends Model
{
    use HasFactory;
    protected $table="user_transactions";
    protected $fillable=[
        "transaction_id",
        "ti_key_id",
        "value"
    ];
    function transaction(){
        return $this->belongsTo(Transaction::class,"transaction_id");
    }
    function transactionKeyId(){
        return $this->belongsTo(TransactionInformationKeys::class,"ti_key_id");
    }

}
