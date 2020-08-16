<?php

namespace App\Traits;

use App\ScheduledTransaction;
use App\Transaction;

trait HasTransactions
{
    public function balance()
    {
        return (int) $this->transactions()->sum('amount');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'trackable');
    }

    public function scheduledTransactions()
    {
        return $this->morphMany(ScheduledTransaction::class, 'trackable');
    }

    public function transfer($account, $from_amount, $to_amount = null)
    {
        $this->withdraw([
            'note' => 'transfer between X and Y',
            'amount' => $from_amount,
        ]);
        $account->deposit([
            'note' => 'transfer between X and Y',
            'amount' => $to_amount ?? $from_amount,
        ]);
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
