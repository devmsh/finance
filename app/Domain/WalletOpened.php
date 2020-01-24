<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class WalletOpened implements ShouldBeStored
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
