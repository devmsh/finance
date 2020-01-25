<?php

namespace App\Domain\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class MoneyWithdrawn implements ShouldBeStored
{
    public $attributes;

    /**
     * WalletOpened constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }
}
