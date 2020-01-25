<?php

namespace App\Domain;

use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\WalletOpened;
use Spatie\EventSourcing\AggregateRoot;

final class WalletAggregateRoot extends AggregateRoot
{
    public function open($data)
    {
        $this->recordThat(new WalletOpened($data));

        return $this;
    }
}
