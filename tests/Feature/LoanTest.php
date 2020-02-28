<?php

namespace Tests\Feature;

use App\Http\Controllers\LoanController;
use App\Http\Requests\LoanRequest;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_log_a_loan()
    {
        $this->withoutExceptionHandling();
        $wallet = factory(Wallet::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->postJson('api/loans', [
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

        $this->assertActionUsesFormRequest(
            LoanController::class,
            'store',
            LoanRequest::class,
        );
    }

    public function test_invalid_loan_creation_return_error_masseges()
    {
        $wallet = factory(Wallet::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->postJson('api/loans')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'wallet_id', 'total', 'payoff_at',
            ]);

        $this->assertActionUsesFormRequest(
            LoanController::class,
            'store',
            LoanRequest::class
        );
    }
}
