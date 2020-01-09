<?php

namespace Tests\Feature;

use App\Goal;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_specify_a_goal()
    {
        $response = $this->post('/api/goals',[
            'name' => "Home",
            'total' => 1000,
            "due_date" => $due_date = Carbon::now()->addYear()
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            "id",
            "name",
            "total",
            "due_date"
        ]);

        $goal = Goal::find(1);
        $this->assertEquals("Home",$goal->name);
        $this->assertEquals(1000,$goal->total);
        $this->assertEquals($due_date,$goal->due_date);
    }

    public function test_goal_may_track_some_transactions()
    {
        /** @var Goal $goal */
        $goal = factory(Goal::class)->create();

        $response = $this->post("/api/goals/{$goal->id}/transactions",[
            'description' => "feb amount",
            'amount' => 100,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            "id",
            "description",
            "amount",
        ]);

        $this->assertCount(1,$goal->transcations);

        $transaction = $goal->transcations->first();
        $this->assertEquals("feb amount",$transaction->description);
        $this->assertEquals(100,$transaction->amount);
    }


}
