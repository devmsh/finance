<?php

namespace Tests\Feature;

use App\Account;
use App\Goal;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_transfer_amount_from_wallet_to_goal()
    {
        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        $wallet = Wallet::find(1);

        $goal = factory(Goal::class)->create();

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => Account::TYPE_WALLET,
            'from_id' => $wallet->uuid,
            'to_type' => Account::TYPE_GOAL,
            'to_id' => $goal->uuid,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(600, $wallet->balance());
        $this->assertEquals(400, $goal->balance());
    }

    public function test_can_transfer_amount_from_goal_to_wallet()
    {
        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        $wallet = Wallet::find(1);

        /** @var Goal $goal */
        $goal = factory(Goal::class)->create();
        $goal->deposit([
            'uuid' => Uuid::uuid4()->toString(),
            'note' => 'test',
            'amount' => 500,
        ]);

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => Account::TYPE_GOAL,
            'from_id' => $goal->uuid,
            'to_type' => Account::TYPE_WALLET,
            'to_id' => $wallet->uuid,
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
            'uuid' => Uuid::uuid4()->toString(),
            'note' => 'test',
            'amount' => 500,
        ]);

        /** @var Goal $secondGoal */
        $secondGoal = factory(Goal::class)->create();
        $secondGoal->deposit([
            'uuid' => Uuid::uuid4()->toString(),
            'note' => 'test',
            'amount' => 500,
        ]);

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => Account::TYPE_GOAL,
            'from_id' => $firstGoal->uuid,
            'to_type' => Account::TYPE_GOAL,
            'to_id' => $secondGoal->uuid,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(100, $firstGoal->balance());
        $this->assertEquals(900, $secondGoal->balance());
    }

    public function test_can_transfer_amount_from_wallet_to_wallet()
    {
        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000,
        ]));
        $firstWallet = Wallet::find(1);

        Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 500,
        ]));
        $secondWallet = Wallet::find(2);

        $response = $this->post('api/transfers', [
            'amount' => 400,
            'from_type' => Account::TYPE_WALLET,
            'from_id' => $firstWallet->uuid,
            'to_type' => Account::TYPE_WALLET,
            'to_id' => $secondWallet->uuid,
        ]);

        $response->assertSuccessful();
        $this->assertEquals(600, $firstWallet->balance());
        $this->assertEquals(900, $secondWallet->balance());
    }
}
