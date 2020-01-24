<?php

namespace App\Domain\Projectors;

use App\Domain\GoalSpecified;
use App\Domain\MonthlyCategoriesBudgetSet;
use App\Domain\MonthlyPlanDefined;
use App\Goal;
use App\Plan;
use Carbon\Carbon;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class GoalProjector implements Projector
{
    use ProjectsEvents;

    public function onGoalSpecified(GoalSpecified $event)
    {
        $attributes = $event->attributes;

        if (! isset($attributes['due_date'])) {
            $periods = Plan::find(1)->expectedPeriods($attributes['total']);
            $attributes['due_date'] = Carbon::today()->addMonths($periods);
        }

        Goal::create($attributes);
    }

    public function onMonthlyPlanDefined(MonthlyPlanDefined $event)
    {
        Plan::create($event->attributes);
    }

    public function onMonthlyCategoriesBudgetSet(MonthlyCategoriesBudgetSet $event)
    {
        foreach ($event->budget as $category_id => $amount) {
            Plan::uuid($event->plan_id)->budgets()->create([
                'category_id' => $category_id,
                'amount' => $amount,
            ]);
        }
    }
}
