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

    public function onMoneyWithdrawn(MoneyWithdrawn $event)
    {
        $data = $event->attributes;
        $data['amount'] *= -1;
        return Wallet::uuid($event->wallet_id)->transactions()->create($data);
    }

    public function onMoneyDeposited(MoneyDeposited $event)
    {
        return Wallet::uuid($event->wallet_id)->transactions()->create($event->attributes);
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
