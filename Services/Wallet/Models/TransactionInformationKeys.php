<?php

namespace Services\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionInformationKeys extends Model
{
    use HasFactory;
    protected $table="transaction_information_keys";
    protected $fillable=[
        "name"
    ];
    function transactionInformation(){
        return $this->hasMany(TransactionInformation::class,"");
    }

}
