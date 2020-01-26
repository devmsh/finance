<?php

namespace Tests\Feature;

use App\Events\GoalAchieved;
use App\Goal;
use App\Plan;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_specify_a_goal()
    {
        Passport::actingAs($user = factory(User::class)->create());
        $response = $this->post('/api/goals', [
            'name' => 'Home',
            'total' => 1000,
            'due_date' => $due_date = Carbon::today()->addYear(),
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'name',
            'total',
            'due_date',
        ]);

        /** @var Goal $goal */
        $goal = Goal::find(1);
        $this->assertEquals('Home', $goal->name);
        $this->assertEquals(1000, $goal->total);
        $this->assertEquals($user->id, $goal->user_id);
        $this->assertEquals($due_date, $goal->due_date);
    }

    public function test_goal_tracks_some_transactions()
    {
        Passport::actingAs($user = factory(User::class)->create());

        /** @var Goal $goal */
        $goal = factory(Goal::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->post("/api/goals/{$goal->id}/transactions", [
            'note' => 'feb amount',
            'amount' => 100,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'note',
            'amount',
        ]);

        $this->assertCount(1, $goal->transactions);

        $transaction = $goal->transactions->first();
        $this->assertEquals('feb amount', $transaction->note);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertInstanceOf(Goal::class, $transaction->trackable);
    }
}
