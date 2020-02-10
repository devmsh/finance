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
}
