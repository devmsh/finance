<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class MoneyDeposited implements ShouldBeStored
{
    public $wallet_id;
    public $attributes;

    /**
     * WalletOpened constructor.
     * @param $wallet_id
     * @param $attributes
     */
    public function __construct($wallet_id, $attributes)
    {
        $this->attributes = $attributes;
        $this->wallet_id = $wallet_id;
    }
}
