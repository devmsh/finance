<?php

namespace Tests\Feature;

use App\Goal;
use App\Loan;
use App\Transaction;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        $response = $this->post('api/wallets', [
            'name' => 'Cash',
            'currency' => 'USD',
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'name',
            'currency',
        ]);

        $wallet = Wallet::find(1);
        $this->assertEquals('Cash', $wallet->name);
        $this->assertEquals('USD', $wallet->currency);

        $transaction = Transaction::find(1);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
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
        $wallet = factory(Wallet::class)->create([
            'currency' => 'USD'
        ]);

        $response = $this->post('api/loans', [
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'total',
            'currency',
            'payoff_at',
        ]);

        $loan = Loan::find(1);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals(Carbon::today()->addYear(), $loan->payoff_at);
        $this->assertEquals('USD', $loan->currency);
    }
}
