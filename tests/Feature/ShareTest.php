<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_share_wallet_with_other_user()
    {
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->attachTo([], $user);
        $otherUser = factory(User::class)->create();

        $this->passportAs($user)
            ->put("api/wallets/{$wallet->id}/users/{$otherUser->id}")
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'wallet_id' => $wallet->id,
                'user_id' => $otherUser->id,
            ]);
    }

    public function test_owner_can_remove_user_from_the_wallet()
    {
        $user = factory(User::class)->create();
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->attachTo([], $user);
        $otherUser = factory(User::class)->create();
        $wallet->share($otherUser);

        $this->passportAs($user)
            ->delete("api/wallets/{$wallet->id}/users/{$otherUser->id}")
            ->assertSuccessful();
    }

    public function test_list_owned_and_shared_wallets()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();
        $otherUser = factory(User::class)->create();
        $wallet->share($otherUser);
        factory(Wallet::class)->create();

        $this->passportAs($otherUser)
            ->get('api/wallets')
            ->assertSuccessful()
            ->assertJsonCount(1);
    }

    public function test_can_access_shared_wallets_details()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();
        $otherUser = factory(User::class)->create();
        $wallet->share($otherUser);

        $this->passportAs($otherUser)
            ->get('api/wallets/1')
            ->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'name',
            ]);
    }

    public function test_wallet_can_track_income_with_category()
    {
        /** @var Wallet $wallet */
        $wallet = factory(Wallet::class)->create();
        $otherUser = factory(User::class)->create();
        $wallet->share($otherUser);

        $category = factory(Category::class)->attachTo([
            'type' => Category::INCOME_TYPE,
        ], $otherUser);

        $this->passportAs($otherUser)
            ->post("api/wallets/{$wallet->id}/income", [
                'note' => 'Salary',
                'amount' => 1000,
                'category_id' => $category->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Salary',
                'amount' => 1000,
                'user_id' => $otherUser->id,
                'category_id' => $category->id,
                'trackable_id' => $wallet->id,
            ]);
    }
}
