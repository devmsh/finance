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
    public function test_can_adjust_wallet_balance($initial_balance ,$new_balance)
    {
        $user = factory(User::class)->create();
        $wallet = Wallet::open([
            'user_id' => $user->id,
            'name' => 'Test',
            'initial_balance' => $initial_balance,
        ]);

        $this->withoutExceptionHandling()
            ->passportAs(factory(User::class)->create())
            ->post("api/wallets/{$wallet->id}/adjust", [
                'new_balance' => $new_balance,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'balance' => $new_balance,
            ]);
    }

    public function walletBalanceProvider()
    {
        return [
            [1000,200],
            [100,200],
            [200,200],
        ];
    }
}
