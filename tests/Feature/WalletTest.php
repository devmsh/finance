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

        collect([0, 100])->each(function($initial_balance) use ($user){
            $response = $this->post('api/wallets', [
                'name' => 'Cash',
                'initial_balance' => $initial_balance,
            ]);

            $response->assertSuccessful();
            $response->assertJsonStructure([
                'id',
                'name',
            ]);

            $transaction = Transaction::all()->last();
            $this->assertEquals($initial_balance, $transaction->amount);
            $this->assertEquals($user->id, $transaction->user_id);
            $this->assertInstanceOf(Wallet::class, $transaction->trackable);
        });
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
        Passport::actingAs($user = factory(User::class)->create());
        factory(Wallet::class)->create();

        $response = $this->get('api/wallets/1');

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'name',
        ]);
    }

    public function test_cannot_access_other_user_wallets()
    {
        factory(Wallet::class)->create();
        Passport::actingAs($user = factory(User::class)->create());
        factory(Wallet::class)->create();

        $response = $this->get('api/wallets/1');

        $response->assertForbidden();
    }
}
