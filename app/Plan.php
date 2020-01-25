<?php

namespace App;

use App\Domain\Events\MonthlyCategoriesBudgetSet;
use App\Domain\Events\MonthlyPlanDefined;
use App\Exceptions\NotAbleToSaveException;

/**
 * @property mixed total_income the total expected income
 * @property mixed must_have the required bills total amount
 * @property mixed min_saving the amount that we are comfortable to save
 * @property mixed pocket_money the rest of the income after the must have and the min saving
 */
class Plan extends Model
{
    protected $guarded = [];

    protected $appends = ['pocket_money'];

    public static function define($attributes)
    {
        event(new MonthlyPlanDefined($attributes));
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function getPocketMoneyAttribute()
    {
        return $this->total_income - $this->must_have - $this->min_saving;
    }

    public function expectedPeriods($amount)
    {
        try {
            return $amount / $this->min_saving;
        } catch (\Exception $exception) {
            throw new NotAbleToSaveException();
        }
    }

    public function setBudget($budget)
    {
        event(new MonthlyCategoriesBudgetSet($this->uuid, $budget));
    }
}
