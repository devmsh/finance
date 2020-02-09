<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use App\Wallet;
use Tests\DatabaseMigrations;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_can_track_income_with_category()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::INCOME_TYPE,
        ], $user);

        $this->passportAs($user)
            ->post("api/wallets/{$wallet->id}/income", [
                'note' => 'Salary',
                'amount' => 1000,
                'category_id' => $category->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Salary',
                'amount' => 1000,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'trackable_id' => $wallet->id,
            ]);
    }

    public function test_wallet_can_track_expenses_with_category()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $this->passportAs($user)
            ->post("api/wallets/{$wallet->id}/expenses", [
                'note' => 'Restaurant',
                'amount' => 100,
                'category_id' => $category->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Restaurant',
                'amount' => -100,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'trackable_id' => $wallet->id,
            ]);
    }

    public function test_can_track_bulk_daily_expenses()
    {
        $user = factory(User::class)->create();

        $firstWallet = factory(Wallet::class)->attachTo([], $user);
        $firstCategory = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $secondWallet = factory(Wallet::class)->attachTo([], $user);
        $secondCategory = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $this->passportAs($user)
            ->post('api/expenses', [
                [
                    'note' => 'Restaurant',
                    'amount' => 100,
                    'wallet_id' => $firstWallet->id,
                    'category_id' => $firstCategory->id,
                ], [
                    'note' => 'Health',
                    'amount' => 200,
                    'wallet_id' => $secondWallet->id,
                    'category_id' => $secondCategory->id,
                ],
            ])
            ->assertSuccessful()
            ->assertJson([
                [
                    'id' => 1,
                    'note' => 'Restaurant',
                    'amount' => -100,
                    'user_id' => $user->id,
                    'category_id' => $firstCategory->id,
                    'trackable_id' => $firstWallet->id,
                ],
                [
                    'id' => 2,
                    'note' => 'Health',
                    'amount' => -200,
                    'user_id' => $user->id,
                    'category_id' => $secondCategory->id,
                    'trackable_id' => $secondWallet->id,
                ],
            ]);
    }
}
