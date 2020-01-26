<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_create_wallet()
    {
        Passport::actingAs($user = factory(User::class)->create());

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
        $this->assertEquals($user->id, $wallet->user_id);

        $transaction = Transaction::find(1);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_list_only_owned_wallets()
    {
        factory(Wallet::class)->create();

        Passport::actingAs($user = factory(User::class)->create());
        factory(Wallet::class)->create();
        $response = $this->get('api/wallets');

        $response->assertSuccessful();
        $response->assertJsonCount(1);
    }

    public function test_can_access_wallets_details()
    {
        factory(Wallet::class)->create();

        Passport::actingAs($user = factory(User::class)->create());
        factory(Wallet::class)->create();

        $response = $this->get('api/wallets/2');

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'name',
        ]);
    }

    public function test_cannot_access_other_user_wallets()
    {
        factory(Wallet::class)->create();

        $user = factory(User::class)->create();
        factory(Wallet::class)->create();

        Passport::actingAs($user);
        $response = $this->get('api/wallets/1');

        $response->assertForbidden();
    }
}
