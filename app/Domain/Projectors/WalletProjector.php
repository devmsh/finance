<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Domain\MoneyDeposited;
use App\Domain\MoneyTransferred;
use App\Domain\MoneyWithdrawn;
use App\Domain\WalletOpened;
use App\Wallet;
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
            'note' => 'initial balance',
            'amount' => $initial_balance,
        ]);
    }

}
