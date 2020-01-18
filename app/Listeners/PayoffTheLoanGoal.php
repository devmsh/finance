<?php

namespace App\Listeners;

use App\Events\LoanRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PayoffTheLoanGoal
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoanRecorded  $event
     * @return void
     */
    public function handle(LoanRecorded $event)
    {
        $loan = $event->loan;

        $loan->goal()->create([
            'name' => 'Pay off loan',
            'total' => $loan->total,
            'due_date' => $loan->payoff_at,
            'currency' => $loan->currency,
        ]);
    }
}
