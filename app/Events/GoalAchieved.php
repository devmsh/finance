<?php

namespace App\Events;

use App\Goal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoalAchieved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Goal
     */
    public $goal;

    /**
     * Create a new event instance.
     *
     * @param  Goal  $goal
     */
    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
