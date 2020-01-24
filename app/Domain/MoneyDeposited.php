<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class MoneyDeposited implements ShouldBeStored
{
    public $attributes;
    public $account_type;
    public $account_id;

    /**
     * WalletOpened constructor.
     * @param $account_type
     * @param $account_id
     * @param $attributes
     */
    public function __construct($account_type, $account_id, $attributes)
    {
        $this->attributes = $attributes;
        $this->account_type = $account_type;
        $this->account_id = $account_id;
    }
}
