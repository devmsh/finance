<?php

namespace Tests\Unit;

use App\Events\GoalAchieved;
use App\Goal;
use App\Plan;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_goal_track_some_transactions()
    {
        $goal = factory(Goal::class)->create();

        $goal->addTransaction(factory(Transaction::class)->data([
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
        Event::fake();

        /** @var Goal $goal */
        $goal = factory(Goal::class)->create([
            'total' => 1000,
        ]);

        $goal->addTransaction([
            'note' => 'feb amount',
            'amount' => 900,
        ]);

        Event::assertNotDispatched(GoalAchieved::class);

        $goal->addTransaction([
            'note' => 'feb amount',
            'amount' => 100,
        ]);

        Event::assertDispatched(GoalAchieved::class, function (GoalAchieved $event) use ($goal) {
            return $event->goal->id == $goal->id;
        });
    }

    public function test_monthly_plan_can_suggest_goal_due_date()
    {
        Passport::actingAs($user = factory(User::class)->create());

        factory(Plan::class)->create([
            'total_income' => 3000,
            'must_have' => 1000,
            'min_saving' => 500,
        ]);

        $goal = Goal::create([
            'name' => 'Home',
            'total' => 1000,
            'user_id' => $user->id,
        ]);

        $this->assertEquals(Carbon::today()->addMonths(2), $goal->due_date);
    }
}
