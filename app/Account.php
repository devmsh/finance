<?php

namespace App;

use App\Domain\MoneyDeposited;
use App\Domain\MoneyTransferred;
use App\Domain\MoneyWithdrawn;

class Account extends Model
{
    const TYPE_WALLET = 'Wallet';
    const TYPE_GOAL = 'Goal';

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'trackable');
    }

    public static function factory($type, $id)
    {
        if ($type == Account::TYPE_WALLET) {
            return Wallet::uuid($id);
        }
        if ($type == Account::TYPE_GOAL) {
            return Goal::uuid($id);
        }
    }

    public function balance()
    {
        return $this->transactions()->sum('amount');
    }

    public function type()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public static function transfer($from_type, $from_id, $from_amount, $to_type, $to_id, $to_amount)
    {
        event(new MoneyTransferred($from_type, $from_id, $from_amount, $to_type, $to_id, $to_amount));
    }

    public function withdraw($data)
    {
        event(new MoneyWithdrawn($this->type(), $this->uuid, $data));
    }

    public function deposit($data)
    {
        event(new MoneyDeposited($this->type(), $this->uuid, $data));
    }
}
