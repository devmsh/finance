<?php

namespace Tests\Feature;

use App\Category;
use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MonthlyPlaningTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_set_our_monthly_plan()
    {
        Passport::actingAs($user = factory(User::class)->create());
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

    public function test_can_specify_monthly_budget_details()
    {
        Passport::actingAs($user = factory(User::class)->create());
        $plan = factory(Plan::class)->create([
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
            'user_id' => $user->id,
        ]);

        $firstCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
            'user_id' => $user->id,
        ]);

        $secondCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
            'user_id' => $user->id,
        ]);

        $response = $this->post("api/plans/{$plan->id}/budget", [
            "{$firstCategory->id}" => 100,
            "{$secondCategory->id}" => 200,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            [
                'id',
                'amount',
                'category' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }
}
