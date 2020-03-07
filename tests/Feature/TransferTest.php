<?php

namespace Tests\Feature;

use App\Goal;
use App\Http\Controllers\TransferController;
use App\Http\Requests\TransferRequest;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transfer_amount_from_wallet_to_goal()
    {
        $user = factory(User::class)->create();

        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
            'user_id' => $user->id,
        ]));

        $goal = factory(Goal::class)->attachTo([], $user);

        $this->passportAs($user)
            ->postJson('api/transfers', [
                'amount' => 400,
                'from_type' => 'wallet',
                'from_id' => $wallet->id,
                'to_type' => 'goal',
                'to_id' => $goal->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'new_from_amount' => 600,
                'new_to_amount' => 400,
            ]);

        $this->assertActionUsesFormRequest(
            TransferController::class,
            'store',
            TransferRequest::class,
        );
    }

    public function test_can_transfer_amount_from_goal_to_wallet()
    {
        $user = factory(User::class)->create();

        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
            'user_id' => $user->id,
        ]));

        // Todo consider having initial balance for goals?
        $goal = tap(factory(Goal::class)->attachTo([], $user), function (Goal $goal) {
            $goal->deposit([
                'note' => 'test',
                'amount' => 500,
            ]);
        });

        $this->passportAs($user)
            ->postJson('api/transfers', [
                'amount' => 400,
                'from_type' => 'goal',
                'from_id' => $goal->id,
                'to_type' => 'wallet',
                'to_id' => $wallet->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'new_from_amount' => 100,
                'new_to_amount' => 1400,
            ]);
    }

    public function test_can_transfer_amount_from_goal_to_goal()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $firstGoal = tap(factory(Goal::class)->attachTo([], $user), function (Goal $goal) {
            $goal->deposit([
                'note' => 'test',
                'amount' => 500,
            ]);
        });

        $secondGoal = tap(factory(Goal::class)->attachTo([], $user), function (Goal $goal) {
            $goal->deposit([
                'note' => 'test',
                'amount' => 500,
            ]);
        });

        $this->passportAs($user)
            ->postJson('api/transfers', [
                'amount' => 400,
                'from_type' => 'goal',
                'from_id' => $firstGoal->id,
                'to_type' => 'goal',
                'to_id' => $secondGoal->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'new_from_amount' => 100,
                'new_to_amount' => 900,
            ]);
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        $user = factory(User::class)->create();

        $firstWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
            'user_id' => $user->id,
        ]));

        $secondWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
            'user_id' => $user->id,
        ]));

        $this->passportAs($user)
            ->postJson('api/transfers', [
                'amount' => 400,
                'from_type' => 'wallet',
                'from_id' => $firstWallet->id,
                'to_type' => 'wallet',
                'to_id' => $secondWallet->id,
            ])
            ->assertSuccessful()
            ->assertJson([
                'new_from_amount' => 600,
                'new_to_amount' => 900,
            ]);
    }

    public function test_invalid_transfer_return_error_messages()
    {
        $this->passportAs(factory(User::class)->create())
            ->postJson('api/transfers')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount',
                'from_type',
                'from_id',
                'to_type',
                'to_id',
            ]);
    }
}
