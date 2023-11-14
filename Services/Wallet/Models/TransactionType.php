<?php

namespace Services\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Services\Wallet\Database\Factories\TransactionTypeFactory;

class TransactionType extends Model
{
    use HasFactory;
    protected $table="transaction_types";
    protected $fillable=["name"];
    protected static function newFactory()
    {
        return TransactionTypeFactory::new();
    }
}
