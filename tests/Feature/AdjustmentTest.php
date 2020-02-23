<?php

namespace Tests\Feature;

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
            ->post("api/wallets/{$wallet->id}/balance", [
                'new_balance' => $new_balance,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'balance' => $new_balance,
            ]);
    }

    /**
     * @dataProvider walletBalanceProvider
     */
    public function test_unauthorized_user_cannot_adjust_wallet_balance($initial_balance, $new_balance)
    {
        $user = factory(User::class)->create();
        $wallet = Wallet::open([
            'user_id' => $user->id,
            'name' => 'Test',
            'initial_balance' => $initial_balance,
        ]);

        $this->passportAs(factory(User::class)->create())
            ->post("api/wallets/{$wallet->id}/balance", [
                'new_balance' => $new_balance,
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
            ->post("api/wallets/{$wallet->id}/openBalance", [
                'new_balance' => 500,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'balance' => 500,
            ]);
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
