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
}
