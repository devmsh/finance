<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\MoneyTransferred;
use App\Domain\Events\MoneyWithdrawn;
use App\Domain\Events\WalletOpened;
use App\Wallet;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class WalletProjector implements Projector
{
    use ProjectsEvents;

    public function onWalletOpened(WalletOpened $event)
    {
        $data = $event->attributes;

        $initial_balance = $data['initial_balance'];
        unset($data['initial_balance']);

        /** @var Wallet $wallet */
        $wallet = Wallet::create($data);

        $wallet->deposit([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'initial balance',
            'amount' => $initial_balance,
        ]);
    }
}
