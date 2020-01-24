<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Domain\MoneyDeposited;
use App\Domain\MoneyTransferred;
use App\Domain\MoneyWithdrawn;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class TransactionProjector implements Projector
{
    use ProjectsEvents;

    public function onMoneyWithdrawn(MoneyWithdrawn $event)
    {
        $data = $event->attributes;
        $data['amount'] *= -1;

        return Account::factory($event->account_type, $event->account_id)
            ->transactions()
            ->create($data);
    }

    public function onMoneyDeposited(MoneyDeposited $event)
    {
        return Account::factory($event->account_type, $event->account_id)
            ->transactions()
            ->create($event->attributes);
    }

    public function onMoneyTransferred(MoneyTransferred $event)
    {
        Account::factory($event->from_type, $event->from_id)->withdraw([
            'note' => 'transfer between X and Y',
            'amount' => $event->from_amount,
        ]);

        Account::factory($event->to_type, $event->to_id)->deposit([
            'note' => 'transfer between X and Y',
            'amount' => $event->to_amount ?? $event->from_amount,
        ]);
    }
}
