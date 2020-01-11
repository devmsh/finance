<?php

namespace Tests\Feature;

use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        $response = $this->post('api/wallets',[
            'name' => 'Cash',
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            "id",
            "name",
        ]);

        $wallet = Wallet::find(1);
        $this->assertEquals("Cash", $wallet->name);
        $this->assertEquals(1000, $wallet->initial_balance);
    }

    // income +
    public function test_wallet_can_receive_income()
    {
        $this->withoutExceptionHandling();
        $wallet = factory(Wallet::class)->create();

        $response = $this->post("api/wallets/{$wallet->id}/income",[
            'note' => 'Salary',
            'amount' => 1000,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'wallet_id',
            'note',
            'amount'
        ]);

        $transaction = Transaction::find(1);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertEquals($wallet->id, $transaction->wallet_id);
    }

    // expenses -
    // transfer (Wallet >> Goal, Wallet >> Wallet)
}
