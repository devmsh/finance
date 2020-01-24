<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class LoanRecorded implements ShouldBeStored
{
    public $attributes;

    /**
     * LoanRecorded constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }
}
