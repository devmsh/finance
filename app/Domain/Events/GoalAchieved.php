<?php

namespace App\Domain\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class GoalAchieved implements ShouldBeStored
{
    public $goal_id;

    /**
     * GoalAchieved constructor.
     * @param $goal_id
     */
    public function __construct($goal_id)
    {
        $this->goal_id = $goal_id;
    }
}
