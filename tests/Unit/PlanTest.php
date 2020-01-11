<?php

namespace Tests\Unit;

use App\Exceptions\NotAbleToSaveException;
use App\Goal;
use App\Plan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_plan_can_suggest_needed_periods_based_on_amount()
    {
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 500,
        ]);

        $periods = $plan->expectedPeriods(1000);

        $this->assertEquals(2,$periods);
    }

    public function test_plan_throw_exception_if_no_saving_can_be_done()
    {
        /** @var Plan $plan */
        $plan = factory(Plan::class)->create([
            'min_saving' => 0,
        ]);

        $this->expectException(NotAbleToSaveException::class);

        $plan->expectedPeriods(1000);
    }
}
