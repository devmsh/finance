<?php

namespace Tests\Feature;

use App\Http\Controllers\WalletAdjustmentController;
use App\Http\Requests\WalletAdjustmentRequest;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdjustmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider walletBalanceProvider
     */
    public function test_can_adjust_wallet_balance($initial_balance, $new_balance)
    {
        $user = factory(User::class)->create();
        $wallet = Wallet::open([
            'user_id' => $user->id,
            'name' => 'Test',
            'initial_balance' => $initial_balance,
        ]);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/balance", [
                'new_balance' => $new_balance,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'balance' => $new_balance,
            ]);

        $this->assertActionUsesFormRequest(
            WalletAdjustmentController::class,
            'balance',
            WalletAdjustmentRequest::class
        );
    }

    public function test_invalid_adjustment_creation_return_error_messages()
    {
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->create(['user_id' => $user->id]);

        $this->passportAs($user)
            ->postJson("api/wallets/{$wallet->id}/balance")
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'new_balance',
            ]);

        $this->assertActionUsesFormRequest(
            WalletAdjustmentController::class,
            'openBalance',
            WalletAdjustmentRequest::class
        );
    }

    public function test_unauthorized_user_cannot_adjust_wallet_balance()
    {
        $user = factory(User::class)->create();
        $wallet = Wallet::open([
            'user_id' => $user->id,
            'name' => 'Test',
            'initial_balance' => 1000,
        ]);

        $this->passportAs(factory(User::class)->create())
            ->postJson("api/wallets/{$wallet->id}/balance", [
                'new_balance' => 2000,
            ])
            ->assertStatus(403);
    }

    public function test_can_adjust_wallet_open_balance()
    {
        $user = factory(User::class)->create();
        $wallet = Wallet::open([
            'user_id' => $user->id,
            'name' => 'Test',
            'initial_balance' => 1000,
        ]);

        $this->passportAs(factory(User::class)->create())
            ->postJson("api/wallets/{$wallet->id}/openBalance", [
                'new_balance' => 500,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'balance' => 500,
            ]);

        $this->assertActionUsesFormRequest(
            WalletAdjustmentController::class,
            'openBalance',
            WalletAdjustmentRequest::class
        );
    }

    public function walletBalanceProvider()
    {
        return [
            [1000, 200],
            [100, 200],
            [200, 200],
        ];
    }
}
