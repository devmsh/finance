<?php

namespace App\Traits;

use App\Transaction;

trait HasTransactions
{
    public function transactions()
    {
        return $this->morphMany(Transaction::class,'trackable');
    }

    public function balance()
    {
        return $this->transactions()->sum('amount');
    }
}
