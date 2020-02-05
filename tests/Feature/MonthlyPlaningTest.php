<?php

namespace Tests\Feature;

use App\Category;
use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
            'user_id' => 2
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'id' => 1,
            'total_income' => 3000,
            'must_have' => 1000,
            'pocket_money' => 1500,
            'min_saving' => 500,
        ]);
    }

    public function test_can_specify_monthly_budget_details()
    {
        Passport::actingAs($user = factory(User::class)->create());
        $plan = factory(Plan::class)->create([
            'user_id' => $user->id
        ]);

        $firstCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES_TYPE,
        ]);

        $secondCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES_TYPE,
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
                'category_id',
            ],
        ]);

        $categories_budget = $response->json();
        $this->assertEquals($firstCategory->id,$categories_budget[0]['category_id']);
        $this->assertEquals(100,$categories_budget[0]['amount']);
        $this->assertEquals($secondCategory->id,$categories_budget[1]['category_id']);
        $this->assertEquals(200,$categories_budget[1]['amount']);
    }

    public function test_user_can_get_monthly_budget()
    {
        $this->fail('TBD');
    }
}
