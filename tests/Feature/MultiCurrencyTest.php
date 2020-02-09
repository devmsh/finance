<?php

namespace Tests\Feature;

use App\Currency;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Tests\DatabaseMigrations;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->post('api/wallets', [
                'name' => 'Cash',
                'currency' => Currency::USD,
                'initial_balance' => 1000,
            ])
            ->assertSuccessful()
            ->assertJson([
                'currency' => Currency::USD
            ]);
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        $user = factory(User::class)->create();

        $firstWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
            'user_id' => $user->id
        ]));

        $secondWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
            'user_id' => $user->id
        ]));

        $this->passportAs($user)
            ->post('api/transfers', [
                'from_amount' => 400,
                'from_type' => 'wallet',
                'from_id' => $firstWallet->id,
                'to_amount' => 200,
                'to_type' => 'wallet',
                'to_id' => $secondWallet->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'new_from_amount' => 600,
                'new_to_amount' => 700
            ]);
    }

    public function test_can_log_a_loan()
    {
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->attachTo([
            'currency' => Currency::USD,
        ], $user);

        $this->passportAs($user)
            ->post('api/loans', [
                'total' => 1000,
                'payoff_at' => Carbon::today()->addYear(),
                'wallet_id' => $wallet->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'currency' => Currency::USD,
            ]);
    }

    public function test_can_specify_a_goal()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', [
                'name' => 'Home',
                'total' => 1000,
                'currency' => Currency::USD,
                'due_date' => $due_date = Carbon::today()->addYear(),
            ])
            ->assertSuccessful()
            ->assertJson([
                'currency' => Currency::USD,
            ]);
    }
}
