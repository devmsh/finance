<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Models\EloquentStoredEvent;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('api/wallets', [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Cash',
            'initial_balance' => 1000,
        ]);

        $response->assertSuccessful();

        $wallet = Wallet::uuid($uuid);
        $this->assertEquals('Cash', $wallet->name);

        $transaction = $wallet->transactions()->first();
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_income()
    {
        $wallet = factory(Wallet::class)->create();

        $response = $this->post("api/wallets/{$wallet->id}/income", [
            'note' => 'Salary',
            'amount' => 1000,
        ]);

        $response->assertSuccessful();

        $transaction = Transaction::find(1);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_expenses()
    {
        $wallet = factory(Wallet::class)->create();

        $response = $this->post("api/wallets/{$wallet->id}/expenses", [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'Restaurant',
            'amount' => 100,
        ]);

        $response->assertSuccessful();

        $transaction = Transaction::uuid($uuid);
        $this->assertEquals('Restaurant', $transaction->note);
        $this->assertEquals(-100, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }
}
