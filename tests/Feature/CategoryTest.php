<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * @property Category salary
 * @property Category commission
 * @property Category food
 * @property Category health
 */
class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_get_categories_list()
    {
        factory(Category::class, 5)->create();

        Passport::actingAs($user = factory(User::class)->create());
        $response = $this->get('api/categories');

        $response->assertSuccessful();
        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'type',
            ],
        ]);
    }

    public function test_can_filter_categories_per_type()
    {
        Passport::actingAs($user = factory(User::class)->create());
        factory(Category::class, 3)->create([
            'type' => Category::INCOME,
            'user_id' => $user->id,
        ]);

        factory(Category::class, 2)->create([
            'type' => Category::EXPENSES,
            'user_id' => $user->id,
        ]);

        $response = $this->get('api/categories?type='.Category::INCOME);
        $response->assertSuccessful();
        $response->assertJsonCount(3);

        $response = $this->get('api/categories?type='.Category::EXPENSES);
        $response->assertSuccessful();
        $response->assertJsonCount(2);
    }

    public function test_can_sort_categories_based_on_usage()
    {
        Passport::actingAs($user = factory(User::class)->create());
        $categoriesTransactions = collect([
            1 => 5,
            2 => 3,
            3 => 7,
        ]);

        factory(Category::class, $categoriesTransactions->count())->create([
            'user_id' => $user->id,
        ]);
        $wallet = factory(Wallet::class)->create();
        $categoriesTransactions->each(function ($category_id, $count) use ($user, $wallet) {
            factory(Transaction::class, $count)->create([
                'trackable_type' => Wallet::class,
                'trackable_id' => $wallet->id,
                'category_id' => $category_id,
            ]);
        });

        $response = $this->get('api/categories?sort=usage');
        $response->assertSuccessful();

        $body = json_decode($response->content());
        $this->assertEquals(3, $body[0]->id);
        $this->assertEquals(1, $body[1]->id);
        $this->assertEquals(2, $body[2]->id);
    }
}
