<?php

namespace Tests\Feature;

use App\Goal;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function test_can_transfer_amount_from_wallet_to_goal()
    {
        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        $goal = factory(Goal::class)->create();

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => 'wallet',
            'from_id' => $wallet->id,
            'to_type' => 'goal',
            'to_id' => $goal->id,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(600, $wallet->balance());
        $this->assertEquals(400, $goal->balance());
    }

    public function test_can_transfer_amount_from_goal_to_wallet()
    {
        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        /** @var Goal $goal */
        $goal = factory(Goal::class)->create();
        $goal->deposit([
            'note' => 'test',
            'amount' => 500,
        ]);

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => 'goal',
            'from_id' => $goal->id,
            'to_type' => 'wallet',
            'to_id' => $wallet->id,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(1400, $wallet->balance());
        $this->assertEquals(100, $goal->balance());
    }

    public function test_can_transfer_amount_from_goal_to_goal()
    {
        /** @var Goal $firstGoal */
        $firstGoal = factory(Goal::class)->create();
        $firstGoal->deposit([
            'note' => 'test',
            'amount' => 500,
        ]);

        /** @var Goal $secondGoal */
        $secondGoal = factory(Goal::class)->create();
        $secondGoal->deposit([
            'note' => 'test',
            'amount' => 500,
        ]);

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => 'goal',
            'from_id' => $firstGoal->id,
            'to_type' => 'goal',
            'to_id' => $secondGoal->id,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(100, $firstGoal->balance());
        $this->assertEquals(900, $secondGoal->balance());
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        $firstWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));

        $secondWallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
        ]));

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => 'wallet',
            'from_id' => $firstWallet->id,
            'to_type' => 'wallet',
            'to_id' => $secondWallet->id,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(600, $firstWallet->balance());
        $this->assertEquals(900, $secondWallet->balance());
    }
}
