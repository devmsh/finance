<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class MonthlyPlanDefined implements ShouldBeStored
{
    public $attributes;

    /**
     * MonthlyPlanDefined constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }
}
