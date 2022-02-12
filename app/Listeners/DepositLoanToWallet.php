<?php

namespace App\Listeners;

use App\Events\LoanRecorded;

class DepositLoanToWallet
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

        $loan->wallet->deposit([
            'note' => 'caused by loan',
            'amount' => $loan->total,
            'causedby_id' => $loan->id,
            'user_id' => $loan->user_id,
        ]);
    }
}
