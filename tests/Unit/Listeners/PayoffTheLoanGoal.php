<?php

namespace Tests\Unit\Listeners;

use App\Events\LoanRecorded;
use App\Goal;
use App\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoffTheLoanGoal extends TestCase
{
    use RefreshDatabase;

    public function test_loan_goal_created()
    {
        $loan = factory(Loan::class)->create();

        $listener = new \App\Listeners\PayoffTheLoanGoal();
        $listener->handle(new LoanRecorded($loan));

        /** @var Goal $goal */
        $goal = $loan->goal;

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($goal->total, $loan->total);
        $this->assertEquals($goal->due_date, $loan->payoff_at);
    }
}
