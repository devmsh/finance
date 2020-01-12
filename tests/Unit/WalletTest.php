<?php

namespace Tests\Unit;

use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_can_track_income()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();

        $wallet->addIncome(factory(Transaction::class)->data([
            'note' => 'Salary',
            'amount' => 1000,
        ]));

        $transaction = Transaction::find(1);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_expense()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();

        $wallet->addExpense(factory(Transaction::class)->data([
            'note' => 'Restaurant',
            'amount' => 100,
        ]));

        $transaction = Transaction::find(1);
        $this->assertEquals('Restaurant', $transaction->note);
        $this->assertEquals(-100, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_total_balance()
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 200,
        ]));

        $this->assertEquals(200, $wallet->balance());

        $wallet->addIncome(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertEquals(300, $wallet->balance());

        $wallet->addExpense(factory(Transaction::class)->data([
            'amount' => 50,
        ]));

        $this->assertEquals(250, $wallet->balance());
    }
}
