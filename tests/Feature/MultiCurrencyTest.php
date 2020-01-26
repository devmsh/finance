<?php

namespace Tests\Feature;

use App\Currency;
use App\Goal;
use App\Loan;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->post('api/wallets', [
            'name' => 'Cash',
            'currency' => Currency::USD,
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();

        $wallet = Wallet::find(1);
        $this->assertEquals(Currency::USD, $wallet->currency);
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $firstWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        $secondWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
        ]));

        $response = $this->post('api/transfers', [
            'from_amount' => 500,
            'from_type' => 'wallet',
            'from_id' => $firstWallet->id,
            'to_amount' => 200,
            'to_type' => 'wallet',
            'to_id' => $secondWallet->id,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(500, $firstWallet->balance());
        $this->assertEquals(700, $secondWallet->balance());
    }

    public function test_can_log_a_loan()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create([
            'currency' => Currency::USD,
        ]);

        $response = $this->post('api/loans', [
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
        ]);

        $response->assertSuccessful();

        $loan = Loan::find(1);
        $this->assertEquals(Currency::USD, $loan->currency);
    }

    public function test_loan_generate_corresponding_goal()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create([
            'currency' => Currency::USD,
        ]);

        $this->post('api/loans', [
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
        ]);

        /** @var Goal $goal */
        $goal = Goal::find(1);

        $this->assertEquals('USD', $goal->currency);
    }

    public function test_can_specify_a_goal()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->post('/api/goals', [
            'name' => 'Home',
            'total' => 1000,
            'currency' => Currency::USD,
            'due_date' => $due_date = Carbon::today()->addYear(),
        ]);

        $response->assertSuccessful();

        /** @var Goal $goal */
        $goal = Goal::find(1);
        $this->assertEquals('USD', $goal->currency);
    }
}
