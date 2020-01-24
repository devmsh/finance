<?php

namespace App;

use App\Domain\MoneyDeposited;
use App\Domain\MoneyTransferred;
use App\Domain\MoneyWithdrawn;

class Account extends Model
{
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'trackable');
    }

    public static function factory($type, $id)
    {
        if ($type == "Wallet") {
            return Wallet::uuid($id);
        }
        if ($type == "Goal") {
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

    public static function transfer($from, $to, $from_amount, $to_amount = null)
    {
        event(new MoneyTransferred(
            $from->type(), $from->uuid, $from_amount,
            $to->type(), $to->uuid, $to_amount
        ));
    }

    public function withdraw($data)
    {
        event(new MoneyWithdrawn($this->uuid, $data));
    }

    public function deposit($data)
    {
        event(new MoneyDeposited($this->uuid, $data));
    }
}
