<?php

namespace Tests\Unit;

use App\Currency;
use App\Goal;
use App\Loan;
use App\Transaction;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_log_a_loan()
    {
        Event::fake();

        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create();

        Loan::create([
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
        ]);

        $loan = Loan::find(1);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals(Carbon::today()->addYear(), $loan->payoff_at);
    }

    public function test_loan_generate_corresponding_transaction()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create();

        $loan = Loan::create([
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
        ]);

        /** @var Transaction $transaction */
        $transaction = $loan->transaction;

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($loan->id, $transaction->causedby->id);
        $this->assertEquals($loan->total, $transaction->amount);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals(1000, $wallet->balance());
    }

    public function test_loan_generate_corresponding_goal()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create();

        $loan = Loan::create([
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
        ]);

        /** @var Goal $goal */
        $goal = $loan->goal;

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($goal->total, $loan->total);
        $this->assertEquals($goal->user_id, $loan->user_id);
        $this->assertEquals($goal->due_date, $loan->payoff_at);
    }

    public function test_loan_generate_corresponding_goal1()
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
}
