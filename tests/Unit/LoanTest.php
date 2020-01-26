<?php

namespace Tests\Unit;

use App\Events\LoanRecorded;
use App\Loan;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_log_a_loan()
    {
        Event::fake();
        $user = factory(User::class)->create();

        $wallet = factory(Wallet::class)->create([
            'user_id' => $user->id
        ]);

        Loan::create([
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
        ]);

        $loan = Loan::find(1);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals(Carbon::today()->addYear(), $loan->payoff_at);
    }
}
