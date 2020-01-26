<?php

namespace App;

use App\Traits\HasTransactions;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasTransactions;

    protected $guarded = [];

    protected $attributes = [
        'currency' => Currency::USD,
    ];

    public static function open($data)
    {
        $initial_balance = $data['initial_balance'];
        unset($data['initial_balance']);

        /** @var Wallet $wallet */
        $wallet = self::create($data);

        $wallet->deposit([
            'note' => 'initial balance',
            'amount' => $initial_balance,
            'user_id' => $data['user_id']
        ]);

        return $wallet;
    }
}
