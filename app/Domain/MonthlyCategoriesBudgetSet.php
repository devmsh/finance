<?php

namespace App\Domain;

use Spatie\EventSourcing\ShouldBeStored;

final class MonthlyCategoriesBudgetSet implements ShouldBeStored
{
    public $budget;
    public $plan_id;

    /**
     * MonthlyCategoriesBudgetSet constructor.
     * @param $plan_id
     * @param $budget
     */
    public function __construct($plan_id, $budget)
    {
        $this->budget = $budget;
        $this->plan_id = $plan_id;
    }
}
