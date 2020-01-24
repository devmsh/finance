<?php

namespace Tests\Unit;

use App\Budget;
use App\Category;
use App\Exceptions\NotAbleToSaveException;
use App\Goal;
use App\Plan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_define_our_monthly_plan()
    {
        Plan::define([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
        ]);

        $plan = Plan::uuid($uuid);
        $this->assertEquals(3000, $plan->total_income);
        $this->assertEquals(1000, $plan->must_have);
        $this->assertEquals(500, $plan->min_saving);
        $this->assertEquals(1500, $plan->pocket_money);
    }

    public function test_plan_can_suggest_needed_periods_based_on_amount()
    {
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 500,
        ]);

        $periods = $plan->expectedPeriods(1000);

        $this->assertEquals(2, $periods);
    }

    public function test_plan_throw_exception_if_no_saving_can_be_done()
    {
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 0,
        ]);

        $this->expectException(NotAbleToSaveException::class);

        $plan->expectedPeriods(1000);
    }

    public function test_can_set_detailed_budget()
    {
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 0,
        ]);

        $firstCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $secondCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $plan->setBudget([
            "{$firstCategory->id}" => 100,
            "{$secondCategory->id}" => 200,
        ]);

        $budgets = Budget::with('category')->get();
        $this->assertCount(2, $budgets);
        $this->assertEquals(100, $budgets[0]->amount);
        $this->assertEquals($firstCategory->id, $budgets[0]->category->id);

        $this->assertEquals(200, $budgets[1]->amount);
        $this->assertEquals($secondCategory->id, $budgets[1]->category->id);
    }
}
