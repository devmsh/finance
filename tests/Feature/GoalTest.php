<?php

namespace Tests\Feature;

use App\Domain\Events\GoalAchieved;
use App\Goal;
use App\Plan;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_specify_a_goal()
    {
        $due_date = Carbon::today()->addYear();

        $response = $this->post('/api/goals', [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Home',
            'total' => 1000,
            'due_date' => $due_date->timestamp,
        ]);

        $response->assertSuccessful();

        /** @var Goal $goal */
        $goal = Goal::uuid($uuid);
        $this->assertEquals('Home', $goal->name);
        $this->assertEquals(1000, $goal->total);
        $this->assertEquals($due_date, $goal->due_date);
    }

    public function test_goal_tracks_some_transactions()
    {
        /** @var Goal $goal */
        $goal = factory(Goal::class)->create();

        $response = $this->post("/api/goals/{$goal->id}/transactions", [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'feb amount',
            'amount' => 100,
        ]);

        $response->assertSuccessful();

        $this->assertCount(1, $goal->transactions);

        $transaction = $goal->transactions->first();
        $this->assertEquals('feb amount', $transaction->note);
        $this->assertEquals(100, $transaction->amount);
        $this->assertInstanceOf(Goal::class, $transaction->trackable);
    }
}
