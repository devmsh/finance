<?php

namespace Tests\Unit;

use App\Goal;
use App\Loan;
use App\Transaction;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_log_a_loan()
    {
        $wallet = factory(Wallet::class)->create();
        $payoff_at = Carbon::today()->addYear();

        Loan::record([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total' => 1000,
            'payoff_at' => $payoff_at->timestamp,
            'wallet_id' => $wallet->id,
        ]);

        $loan = Loan::uuid($uuid);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals($payoff_at, $loan->payoff_at);
    }

    public function test_loan_generate_corresponding_transaction()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();
        Loan::record([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear()->timestamp,
            'wallet_id' => $wallet->id,
        ]);

        $loan = Loan::find(1);
        /** @var Transaction $transaction */
        $transaction = $loan->transaction;

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($loan->id, $transaction->causedby->id);
        $this->assertEquals($loan->total, $transaction->amount);
        $this->assertEquals(1000, $wallet->balance());
    }

    public function test_loan_generate_corresponding_goal()
    {
        $wallet = factory(Wallet::class)->create();
        Loan::record([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear()->timestamp,
            'wallet_id' => $wallet->id,
        ]);

        $loan = Loan::find(1);
        /** @var Goal $goal */
        $goal = $loan->goal;

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($goal->total, $loan->total);
        $this->assertEquals($goal->due_date, $loan->payoff_at);
    }
}
