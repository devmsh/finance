<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Carbon\Carbon;
use Tests\DatabaseMigrations;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_log_a_loan()
    {
        $wallet = factory(Wallet::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->post('api/loans', [
                'total' => 1000,
                'payoff_at' => Carbon::today()->addYear(),
                'wallet_id' => $wallet->id,
            ])->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'total' => 1000,
                'user_id' => $user->id,
                'payoff_at' => Carbon::today()->addYear(),
            ]);
    }
}
