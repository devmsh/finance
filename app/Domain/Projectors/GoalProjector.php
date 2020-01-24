<?php

namespace App\Domain\Projectors;

use App\Domain\GoalSpecified;
use App\Domain\MonthlyCategoriesBudgetSet;
use App\Domain\MonthlyPlanDefined;
use App\Goal;
use App\Plan;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class GoalProjector implements Projector
{
    use ProjectsEvents;

    public function onGoalSpecified(GoalSpecified $event)
    {
        Goal::create($event->attributes);
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
