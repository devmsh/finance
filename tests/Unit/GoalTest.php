<?php

namespace Tests\Unit;

use App\Goal;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_goal_may_track_some_transactions()
    {
        $goal = factory(Goal::class)->create();

        $goal->addTransaction(factory(Transaction::class)->data([
            'description' => "feb amount",
            'amount' => 100,
        ]));

        $transaction = $goal->transactions->first();
        $this->assertEquals("feb amount",$transaction->description);
        $this->assertEquals(100,$transaction->amount);
    }

    public function test_can_check_if_goal_is_achieved()
    {
        /** @var Goal $goal */
        $goal = factory(Goal::class)->create([
            'total' => 1000,
        ]);

        $goal->addTransaction(factory(Transaction::class)->data([
            'amount' => 900,
        ]));

        $this->assertFalse($goal->isAchieved());

        $goal->addTransaction(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertTrue($goal->isAchieved());
    }

    public function test_can_get_goal_progress()
    {
        /** @var Goal $goal */
        $goal = factory(Goal::class)->create([
            'total' => 1000,
        ]);

        $goal->addTransaction(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertEquals(100,$goal->trackedAmount());

        $goal->addTransaction(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertEquals(200,$goal->trackedAmount());
    }
}
