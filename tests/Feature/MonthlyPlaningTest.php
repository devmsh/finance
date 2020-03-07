<?php

namespace Tests\Feature;

use App\Category;
use App\Http\Controllers\PlanController;
use App\Http\Requests\PlanRequest;
use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyPlaningTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_set_our_monthly_plan()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->postJson('api/plans', [
                'total_income' => 3000,
                'must_have' => 1000,
                'min_saving' => 500,
                'user_id' => auth()->id(),
            ])->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'total_income' => 3000,
                'must_have' => 1000,
                'pocket_money' => 1500,
                'min_saving' => 500,
            ]);

        $this->assertActionUsesFormRequest(
            PlanController::class,
            'store',
            PlanRequest::class
        );
    }

    public function test_invalid_plan_creation_return_error_masseges()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->postJson('api/plans')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'total_income', 'must_have', 'min_saving', 'user_id'
            ]);

        $this->assertActionUsesFormRequest(
            PlanController::class,
            'store',
            PlanRequest::class
        );
    }

    public function test_can_specify_monthly_budget_details()
    {
        $user = factory(User::class)->create();

        $plan = factory(Plan::class)->attachTo([], $user);

        $firstCategory = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $secondCategory = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $this->passportAs($user)
            ->postJson("api/plans/{$plan->id}/budget", [
                "{$firstCategory->id}" => 100,
                "{$secondCategory->id}" => 200,
            ])
            ->assertSuccessful()
            ->assertJson([
                [
                    'id' => 1,
                    'amount' => 100,
                    'category_id' => 1,
                ],
                [
                    'id' => 2,
                    'amount' => 200,
                    'category_id' => 2,
                ],
            ]);
    }
}
