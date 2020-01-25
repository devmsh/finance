<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\MoneyTransferred;
use App\Domain\Events\MoneyWithdrawn;
use Ramsey\Uuid\Uuid;
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

    public function onMoneyDeposited(MoneyDeposited $event, string $aggregateUuid)
    {
        return Account::factory(Account::TYPE_WALLET, $aggregateUuid)
            ->transactions()
            ->create($event->attributes);
    }

    public function onMoneyTransferred(MoneyTransferred $event)
    {
        Account::factory($event->from_type, $event->from_id)->withdraw([
            'uuid' => Uuid::uuid4()->toString(),
            'note' => 'transfer between X and Y',
            'amount' => $event->from_amount,
        ]);

        Account::factory($event->to_type, $event->to_id)->deposit([
            'uuid' => Uuid::uuid4()->toString(),
            'note' => 'transfer between X and Y',
            'amount' => $event->to_amount ?? $event->from_amount,
        ]);
    }
}
