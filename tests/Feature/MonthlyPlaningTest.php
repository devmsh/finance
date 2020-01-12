<?php

namespace Tests\Feature;

use App\Goal;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MonthlyPlaningTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_set_our_monthly_plan()
    {
        $response = $this->post('api/plans', [
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'total_income',
            'must_have',
            'pocket_money',
            'min_saving',
        ]);

        $plan = Plan::find(1);
        $this->assertEquals(3000, $plan->total_income);
        $this->assertEquals(1000, $plan->must_have);
        $this->assertEquals(500, $plan->min_saving);
        $this->assertEquals(1500, $plan->pocket_money);
    }

    public function test_monthly_plan_can_suggest_goal_due_date()
    {
        $plan = factory(Plan::class)->create([
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
        ]);

        $response = $this->post('/api/goals', [
            'name' => 'Home',
            'total' => 1000,
        ]);

        $goal = Goal::find(1);
        $this->assertEquals(Carbon::today()->addMonths(2), $goal->due_date);
    }
}
