<?php

namespace App\Traits;

use App\Transaction;

trait HasTransactions
{
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'trackable');
    }

    public function balance()
    {
        return $this->transactions()->sum('amount');
    }

    public function transfer($account, $amount)
    {
        $data = [
            'note' => 'transfer between X and Y',
            'amount' => $amount
        ];
        $this->withdraw($data);
        $account->deposit($data);
    }

    public function withdraw($data)
    {
        $data['amount'] *= -1;

        return $this->transactions()->create($data);
    }

    public function deposit($data)
    {
        return $this->transactions()->create($data);
    }
}
