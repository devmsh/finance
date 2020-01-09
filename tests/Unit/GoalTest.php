<?php

namespace Tests\Unit;

use App\Goal;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class GoalTest extends TestCase
{
    use DatabaseMigrations;

    public function test_goal_may_track_some_transactions()
    {
        $goal = new Goal();

        $goal->create([
            'name' => 'test',
            'total' => 1000,
            'due_date' => Carbon::now()->addYear()
        ]);

//        $goal->addTransaction([
//            'description' => "feb amount",
//            'amount' => 100,
//        ]);
//
//        $transaction = $goal->transcations->first();
//        $this->assertEquals("feb amount",$transaction->description);
//        $this->assertEquals(100,$transaction->amount);
    }
}
