<?php

namespace App\Domain\Projectors;

use App\Domain\Events\LoanRecorded;
use App\Goal;
use App\Loan;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class LoanProjector implements Projector
{
    use ProjectsEvents;

    public function onLoanRecorded(LoanRecorded $event)
    {
        $loan = Loan::create($event->attributes);

        $loan->wallet->deposit([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'caused by loan',
            'amount' => $loan->total,
            'causedby_id'=> $loan->id,
        ]);

        Goal::specify([
            'loan_id' => $loan->id,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Pay off loan',
            'total' => $loan->total,
            'due_date' => $loan->payoff_at->timestamp,
            'currency' => $loan->currency,
        ]);
    }
}
