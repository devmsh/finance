<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_wallet()
    {
        collect([0, 100])->each(function ($initial_balance, $key) {
            $this->passportAs(factory(User::class)->create())
                ->post('api/wallets', [
                    'name' => 'Cash',
                    'initial_balance' => $initial_balance,
                ])
                ->assertSuccessful()
                ->assertJson([
                    'id' => $key + 1,
                    'name' => 'Cash',
                    'balance' => $initial_balance
                ]);
        });
    }

    public function test_list_only_owned_wallets()
    {
        factory(Wallet::class)->create();
        $user = factory(User::class)->create();
        factory(Wallet::class)->attachTo([], $user);

        $this->passportAs($user)
            ->get('api/wallets')
            ->assertSuccessful()
            ->assertJsonCount(1);
    }

    public function test_can_access_wallets_details()
    {
        factory(Wallet::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->get('api/wallets/1')
            ->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'name',
            ]);
    }

    public function test_cannot_access_other_user_wallets()
    {
        factory(Wallet::class)->create();
        $user = factory(User::class)->create();
        factory(Wallet::class)->attachTo([], $user);

        $this->passportAs($user)
            ->get('api/wallets/1')
            ->assertForbidden();
    }
}
