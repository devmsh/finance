<?php

namespace Tests\Unit;

use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_can_receive_income()
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
        $this->assertEquals($wallet->id, $transaction->wallet_id);
    }
}
