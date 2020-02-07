<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_users_have_their_own_categories_copies()
    {
        factory(Category::class, 5)->create();

        // TODO replace with user creation endpoint
        Passport::actingAs($user = factory(User::class)->create());

        $this->assertEquals(5, Category::whereNull('user_id')->count());
        $this->assertEquals(5, Category::where('user_id', $user->id)->count());
    }

    public function test_can_get_categories_list()
    {
        factory(Category::class, 5)->create();

        Passport::actingAs($user = factory(User::class)->create());

        $this->get('api/categories')->assertSuccessful()
            ->assertJsonPaths('*.user_id', $user->id)
            ->assertJsonCount(5)
            ->assertJsonStructure([
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

        $scenarios = [
            Category::INCOME_TYPE => 3,
            Category::EXPENSES_TYPE => 2,
            Category::TRANSFER_TYPE => 4,
            '999' => 0,
        ];

        collect($scenarios)->each(function ($type, $count) use ($user) {
            factory(Category::class, $count)->create([
                'type' => $type,
                'user_id' => $user->id,
            ]);

            $this->get('api/categories?type=' . $type)
                ->assertSuccessful()
                ->assertJsonCount($count);
        });
    }

    public function test_can_sort_categories_based_on_usage()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $transactionsCount = [
            1 => 5,
            2 => 3,
            3 => 7
        ];

        foreach ($transactionsCount as $count) {
            factory(Transaction::class, $count)->create([
                'category_id' => factory(Category::class)->create()->id,
            ]);
        }

        $this->get('api/categories?sort=usage')
            ->assertSuccessful()
            ->assertJsonPath('*.id', [3, 1, 2]);
    }
}
