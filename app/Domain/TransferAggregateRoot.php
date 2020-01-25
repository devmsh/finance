<?php

namespace App\Domain;

use App\Domain\Events\MoneyTransferred;
use App\Domain\Events\MoneyWithdrawn;
use Spatie\EventSourcing\AggregateRoot;

final class TransferAggregateRoot extends AggregateRoot
{
    public function transfer($attributes)
    {
        $this->recordThat(new MoneyTransferred($attributes));

        return $this;
    }

    public function applyMoneyTransferred(MoneyTransferred $event)
    {
        WalletAggregateRoot::retrieve($event->from_id)->deposit([
            'note' => 'transfer between X and Y',
            'amount' => $event->from_amount,
        ])->persist();

        WalletAggregateRoot::retrieve($event->to_id)->deposit([
            'note' => 'transfer between X and Y',
            'amount' => $event->to_amount,
        ])->persist();
    }
}
