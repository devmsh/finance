<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function test_can_create_wallet()
    {
        $response = $this->post('api/wallets', [
            'name' => 'Cash',
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'name',
        ]);

        $wallet = Wallet::find(1);
        $this->assertEquals('Cash', $wallet->name);

        $transaction = Transaction::find(1);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }
}
