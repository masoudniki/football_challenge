<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionInformation extends Model
{
    use HasFactory;
    protected $table="transactions_information";
    protected $fillable=[
        "transaction_id",
        "ti_key_id",
        "value"
    ];
    protected $with=["transactionKey"];
    function transaction(){
        return $this->belongsTo(Transaction::class,"transaction_id");
    }
    function transactionKey(){
        return $this->belongsTo(TransactionInformationKeys::class,"ti_key_id");
    }

}
