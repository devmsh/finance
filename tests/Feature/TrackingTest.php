<?php

namespace Tests\Feature;

use App\Category;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\WalletExpenseController;
use App\Http\Requests\DailyExpenseRequest;
use App\Http\Requests\WalletExpenseIncomeRequest;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_can_track_income_with_category()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::INCOME_TYPE,
        ], $user);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/income", [
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

        $this->assertActionUsesFormRequest(
            WalletExpenseController::class,
            'store',
            WalletExpenseIncomeRequest::class
        );
    }

    public function test_wallet_can_track_expenses_with_category()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::EXPENSES_TYPE,
        ], $user);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/expenses", [
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

        $this->assertActionUsesFormRequest(
            WalletExpenseController::class,
            'store',
            WalletExpenseIncomeRequest::class
        );
    }

    public function test_invalid_wallet_expenses_and_income_creation_return_errors()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([], $user);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/expenses")
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'note', 'amount', 'category_id',
            ]);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/income")
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'note', 'amount', 'category_id',
            ]);

        $this->assertActionUsesFormRequest(
            WalletExpenseController::class,
            'store',
            WalletExpenseIncomeRequest::class
        );
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
            ->postJson('api/expenses', [
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

        $this->assertActionUsesFormRequest(
            ExpenseController::class,
            'store',
            DailyExpenseRequest::class
        );
    }

    public function test_invalid_daily_expenses_creation_return_error_messages()
    {
        $user = factory(User::class)->create();

        $this->passportAs($user)
            ->postJson('api/expenses', [[]])
            ->assertStatus(422);

        $this->assertActionUsesFormRequest(
            ExpenseController::class,
            'store',
            DailyExpenseRequest::class
        );
    }
}
