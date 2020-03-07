<?php

namespace Tests\Feature;

use App\Goal;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalTransactionController;
use App\Http\Requests\GoalRequest;
use App\Http\Requests\GoalTransactionRequest;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_specify_a_goal()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->postJson('api/goals', [
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date = Carbon::today()->addYear(),
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date,
            ]);

        $this->assertActionUsesFormRequest(
            GoalController::class,
            'store',
            GoalRequest::class
        );
    }

    public function test_invalid_goal_creation_return_clear_validation_messages()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->postJson('api/goals')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'total',
                'due_date',
            ]);
    }

    public function test_goal_tracks_some_transactions()
    {
        $goal = factory(Goal::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->postJson("api/goals/{$goal->id}/transactions", [
                'note' => 'feb amount',
                'amount' => 100,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'feb amount',
                'amount' => 100,
            ]);

        $this->assertActionUsesFormRequest(
            GoalTransactionController::class,
            'store',
            GoalTransactionRequest::class,
        );
    }

    public function test_invalid_goal_transaction_creation_return_clear_validation_messages()
    {
        $goal = factory(Goal::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->postJson("api/goals/{$goal->id}/transactions")
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'note',
                'amount',
            ]);

        $this->assertActionUsesFormRequest(
            GoalTransactionController::class,
            'store',
            GoalTransactionRequest::class,
        );
    }
}
