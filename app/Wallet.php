<?php

namespace App;

use App\Domain\Events\WalletOpened;

class Wallet extends Account
{
    protected $guarded = [];

    protected $attributes = [
        'currency' => Currency::USD,
    ];

    public static function open($data)
    {
        event(new WalletOpened($data));
    }
}
