<?php

namespace Tests\Unit;

use App\Domain\GoalAchieved;
use App\Goal;
use App\Plan;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_specify_a_goal()
    {
        $due_date = Carbon::today()->addYear();

        Goal::specify([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Home',
            'total' => 1000,
            'due_date' => $due_date->timestamp,
        ]);

        /** @var Goal $goal */
        $goal = Goal::uuid($uuid);
        $this->assertEquals('Home', $goal->name);
        $this->assertEquals(1000, $goal->total);
        $this->assertEquals($due_date, $goal->due_date);
    }

    public function test_goal_track_some_transactions()
    {
        $goal = factory(Goal::class)->create();

        $goal->addTransaction(factory(Transaction::class)->data([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'feb amount',
            'amount' => 100,
        ]));

        $transaction = $goal->transactions->first();
        $this->assertEquals('feb amount', $transaction->note);
        $this->assertEquals(100, $transaction->amount);
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

        $this->assertEquals(100, $goal->balance());

        $goal->addTransaction(factory(Transaction::class)->data([
            'amount' => 100,
        ]));

        $this->assertEquals(200, $goal->balance());
    }

    public function test_detect_that_goal_is_achieved()
    {
        Event::fake([
            GoalAchieved::class,
        ]);

        /** @var Goal $goal */
        $goal = factory(Goal::class)->create([
            'total' => 1000,
        ]);

        $goal->addTransaction([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'feb amount',
            'amount' => 900,
        ]);

        Event::assertNotDispatched(GoalAchieved::class);

        $goal->addTransaction([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'feb amount',
            'amount' => 100,
        ]);

        Event::assertDispatched(GoalAchieved::class, function (GoalAchieved $event) use ($goal) {
            return $event->goal_id == $goal->uuid;
        });
    }

    public function test_monthly_plan_can_suggest_goal_due_date()
    {
        $plan = factory(Plan::class)->create([
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
        ]);

        Goal::specify([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Home',
            'total' => 1000,
        ]);

        $goal = Goal::find(1);
        $this->assertEquals(Carbon::today()->addMonths(2), $goal->due_date);
    }
}
