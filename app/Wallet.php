<?php

namespace App;

use App\Traits\HasTransactions;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasTransactions;
    

    protected $fillable = ['name'];

    public static function open($data)
    {
        $initial_balance = $data['initial_balance'];

        /** @var Wallet $wallet */
        $wallet = self::create($data);

        $wallet->addIncome([
            'note' => 'initial balance',
            'amount' => $initial_balance
        ]);
        return $wallet;
    }

    public function addIncome($data)
    {
        return $this->transactions()->create($data);
    }

    public function addExpense($data)
    {
        $data['amount'] *= -1;
        return $this->transactions()->create($data);
    }
}
