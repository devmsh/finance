<?php

namespace Tests\Feature;

use App\Account;
use App\Currency;
use App\Goal;
use App\Loan;
use App\Transaction;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        $response = $this->post('api/wallets', [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Cash',
            'currency' => Currency::USD,
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();

        $wallet = Wallet::uuid($uuid);
        $this->assertEquals('Cash', $wallet->name);
        $this->assertEquals(Currency::USD, $wallet->currency);

        $transaction = Transaction::find(1);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        $this->withoutExceptionHandling();

        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        $firstWallet = Wallet::find(1);

        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
        ]));
        $secondWallet = Wallet::find(2);

        $response = $this->post('api/transfers', [
            'from_type' => Account::TYPE_WALLET,
            'from_id' => $firstWallet->uuid,
            'from_amount' => 500,
            'to_type' => Account::TYPE_WALLET,
            'to_id' => $secondWallet->uuid,
            'to_amount' => 200,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(500, $firstWallet->balance());
        $this->assertEquals(700, $secondWallet->balance());
    }

    public function test_can_log_a_loan()
    {
        $wallet = factory(Wallet::class)->create([
            'currency' => Currency::USD,
        ]);

        $response = $this->post('api/loans', [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear()->timestamp,
            'wallet_id' => $wallet->id,
        ]);

        $response->assertSuccessful();

        $loan = Loan::uuid($uuid);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals(Carbon::today()->addYear(), $loan->payoff_at);
        $this->assertEquals(Currency::USD, $loan->currency);
    }

    public function test_loan_generate_corresponding_goal()
    {
        $this->withoutExceptionHandling();

        $wallet = factory(Wallet::class)->create([
            'currency' => Currency::USD,
        ]);

        Loan::record([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear()->timestamp,
            'wallet_id' => $wallet->id,
        ]);

        $loan = Loan::uuid($uuid);
        /** @var Goal $goal */
        $goal = $loan->goal;

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($goal->total, $loan->total);
        $this->assertEquals($goal->due_date, $loan->payoff_at);
        $this->assertEquals('USD', $goal->currency);
    }

    public function test_can_specify_a_goal()
    {
        $due_date = Carbon::today()->addYear();

        $response = $this->post('/api/goals', [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Home',
            'total' => 1000,
            'currency' => Currency::USD,
            'due_date' => $due_date->timestamp,
        ]);

        $response->assertSuccessful();

        /** @var Goal $goal */
        $goal = Goal::uuid($uuid);
        $this->assertEquals('Home', $goal->name);
        $this->assertEquals(1000, $goal->total);
        $this->assertEquals($due_date, $goal->due_date);
        $this->assertEquals('USD', $goal->currency);
    }
}
