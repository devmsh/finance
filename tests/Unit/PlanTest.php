<?php

namespace Tests\Unit;

use App\Budget;
use App\Category;
use App\Exceptions\NotAbleToSaveException;
use App\Http\Requests\PlanRequest;
use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_rules_on_plan()
    {
        $this->assertEquals([
            'total_income' => 'required|numeric',
            'must_have' => 'required|numeric',
            'min_saving' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ], (new PlanRequest())->rules());
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
        Passport::actingAs($user = factory(User::class)->create());
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 0,
        ]);

        $firstCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES_TYPE,
        ]);

        $secondCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES_TYPE,
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
