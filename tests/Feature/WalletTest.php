<?php

namespace Tests\Feature;

use App\Http\Controllers\WalletController;
use App\Http\Requests\WalletRequest;
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
                ->postJson('api/wallets', [
                    'name'            => 'Cash',
                    'initial_balance' => $initial_balance,
                ])
                ->assertSuccessful()
                ->assertJson([
                    'id'      => $key + 1,
                    'name'    => 'Cash',
                    'balance' => $initial_balance,
                ]);
        });

        $this->assertActionUsesFormRequest(
            WalletController::class,
            'store',
            WalletRequest::class
        );

    }

    public function test_invalid_wallet_creation_return_error_messages()
    {
        $this->passportAs(factory(User::class)->create())
            ->postJson('api/wallets')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->assertActionUsesFormRequest(
            WalletController::class,
            'store',
            WalletRequest::class
        );
    }

    public function test_list_only_owned_wallets()
    {
        factory(Wallet::class)->create();
        $user = factory(User::class)->create();
        factory(Wallet::class)->attachTo([], $user);

        $this->passportAs($user)
            ->getJson('api/wallets')
            ->assertSuccessful()
            ->assertJsonCount(1);
    }

    public function test_can_access_wallets_details()
    {
        factory(Wallet::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->getJson('api/wallets/1')
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
            ->getJson('api/wallets/1')
            ->assertForbidden();
    }
}
