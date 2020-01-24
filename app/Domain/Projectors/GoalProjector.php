<?php

namespace App\Domain\Projectors;

use App\Domain\GoalSpecified;
use App\Goal;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class GoalProjector implements Projector
{
    use ProjectsEvents;

    public function onGoalSpecified(GoalSpecified $event)
    {
        Goal::create($event->attributes);
    }
}
