<?php

namespace Tests\Feature;

use App\Category;
use App\ScheduledTransaction;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecurrentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_schedule_monthly_transaction()
    {
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::INCOME_TYPE,
        ], $user);

        $this->passportAs($user)
            ->post("api/wallets/{$wallet->id}/schedule", [
                'note' => 'Salary',
                'amount' => 1000,
                'category_id' => $category->id,
                'period' => ScheduledTransaction::MONTHLY,
                'at' => '1',
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Salary',
                'amount' => 1000,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'trackable_id' => $wallet->id,
                'period' => ScheduledTransaction::MONTHLY,
                'at' => '1',
            ]);
    }
}
