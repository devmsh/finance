<?php

namespace Tests\Unit;

use App\Account;
use App\Domain\WalletAggregateRoot;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TransactionProjectorTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_initial_balance()
    {
        $uuid = Uuid::uuid4()->toString();

        WalletAggregateRoot::retrieve($uuid)->open([
            'uuid' => $uuid,
            'name' => 'Cash',
            'initial_balance' => 1000,
        ])->persist();

        $transaction = Transaction::find(1);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_income()
    {
        $uuid = Uuid::uuid4()->toString();

        WalletAggregateRoot::retrieve($uuid)->open([
            'uuid' => $uuid,
            'name' => 'Cash',
            'initial_balance' => 1000,
        ])->deposit([
            'note' => 'Salary',
            'amount' => 1000,
        ])->persist();

        $transaction = Transaction::find(1);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_expense()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();

        $wallet->withdraw(factory(Transaction::class)->data([
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
        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 200,
        ]));

        /** @var Wallet $wallet */
        $wallet = Wallet::find(1);

        $this->assertEquals(200, $wallet->balance());

        $wallet->deposit(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertEquals(300, $wallet->balance());

        $wallet->withdraw(factory(Transaction::class)->data([
            'amount' => 50,
        ]));

        $this->assertEquals(250, $wallet->balance());
    }

    public function test_can_transfer_amount_to_other_account()
    {
        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        /** @var Wallet $firstWallet */
        $firstWallet = Wallet::find(1);

        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        $secondWallet = Wallet::find(2);

        Account::transfer(
            $firstWallet->type(), $firstWallet->uuid, 400,
            $secondWallet->type(), $secondWallet->uuid, 400);

        $this->assertEquals(600, $firstWallet->balance());
        $this->assertEquals(1400, $secondWallet->balance());
    }
}
