<?php

namespace Tests\Unit;

use App\Http\Requests\WalletRequest;
use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_reules_for_wallet()
    {
        $this->assertEquals([
            'name' => 'required|string',
        ], (new WalletRequest())->rules());
    }

    public function test_can_create_wallet()
    {
        $user = factory(User::class)->create();

        collect([0, 100])->each(function ($initial_balance) use ($user) {
            $this->passportAs($user)
                ->post('api/wallets', [
                    'name' => 'Cash',
                    'initial_balance' => $initial_balance,
                ])
                ->assertSuccessful()
                ->assertJsonStructure([
                    'id',
                    'name',
                ]);

            $transaction = Transaction::all()->last();
            $this->assertEquals($initial_balance, $transaction->amount);
            $this->assertEquals($user->id, $transaction->user_id);
            $this->assertInstanceOf(Wallet::class, $transaction->trackable);
        });
    }

    public function test_wallet_can_track_income()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();

        $wallet->deposit(factory(Transaction::class)->data([
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
        /** @var Wallet $wallet */
        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 200,
        ]));

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
        /** @var Wallet $firstWallet */
        $firstWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        $secondWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        $firstWallet->transfer($secondWallet, 400);

        $this->assertEquals(600, $firstWallet->balance());
        $this->assertEquals(1400, $secondWallet->balance());
    }
}
