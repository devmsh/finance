<?php

namespace Tests\Feature;

use App\Goal;
use App\Loan;
use App\Transaction;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_log_a_loan()
    {
        $wallet = factory(Wallet::class)->create();

        $response = $this->post('api/loans', [
            'total' => 1000,
            'payoff_at' => Carbon::today()->addYear(),
            'wallet_id' => $wallet->id,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'total',
            'payoff_at',
        ]);

        $loan = Loan::find(1);
        $this->assertEquals(1000, $loan->total);
        $this->assertEquals(Carbon::today()->addYear(), $loan->payoff_at);
    }
}
