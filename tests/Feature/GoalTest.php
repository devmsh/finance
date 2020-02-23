<?php

namespace Tests\Feature;

use App\Goal;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_specify_a_goal()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', [
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date = Carbon::today()->addYear(),
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date,
            ]);
    }

    public function test_goal_tracks_some_transactions()
    {
        $goal = factory(Goal::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->post("/api/goals/{$goal->id}/transactions", [
                'note' => 'feb amount',
                'amount' => 100,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'feb amount',
                'amount' => 100,
            ]);
    }

    public function test_goal_require_a_name()
    {
        $goal = factory(Goal::class)->raw(['name' => '']);

        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', $goal)
            ->assertSessionHasErrors('name');
    }

    public function test_goal_require_a_total()
    {
        $goal = factory(Goal::class)->raw(['total' => '']);

        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', $goal)
            ->assertSessionHasErrors('total');
    }

    public function test_goal_require_due_date()
    {
        $goal = factory(Goal::class)->raw(['due_date' => '']);

        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', $goal)
            ->assertSessionHasErrors('due_date');

        $goal = factory(Goal::class)->raw(['due_date' => 'DateThatNotData']);
        $this->passportAs($user)
            ->post('/api/goals', $goal)
            ->assertSessionHasErrors('due_date');
    }
}
