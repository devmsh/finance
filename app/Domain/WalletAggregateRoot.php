<?php

namespace App\Domain;

use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\MoneyWithdrawn;
use App\Domain\Events\WalletOpened;
use Spatie\EventSourcing\AggregateRoot;

final class WalletAggregateRoot extends AggregateRoot
{
    public $balance = 0;

    public function open($data)
    {
        $this->recordThat(new WalletOpened($data));

        return $this;
    }

    public function applyWalletOpened(WalletOpened $event)
    {
        $this->deposit([
            'note' => 'initial balance',
            'amount' => $event->attributes['initial_balance'],
        ]);
    }

    public function deposit($data)
    {
        $this->recordThat(new MoneyDeposited($data));

        return $this;
    }

    public function withdraw($data)
    {
        $this->recordThat(new MoneyWithdrawn($data));

        return $this;
    }
}
