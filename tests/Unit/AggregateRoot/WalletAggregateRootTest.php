<?php

namespace Tests\Unit\AggregateRoot;

use App\Account;
use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\MoneyTransferred;
use App\Domain\Events\MoneyWithdrawn;
use App\Domain\Events\WalletOpened;
use App\Domain\TransferAggregateRoot;
use App\Domain\WalletAggregateRoot;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class WalletAggregateRootTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_open_wallet()
    {
        WalletAggregateRoot::fake()->open([
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'name' => 'Cash',
            'initial_balance' => 1000,
        ])->assertRecorded([
            new WalletOpened([
                'uuid' => $uuid,
                'name' => 'Cash',
                'initial_balance' => 1000,
            ]),
            new MoneyDeposited([
                'note' => 'initial balance',
                'amount' => 1000,
            ]),
        ]);
    }

    public function test_wallet_can_track_income()
    {
        WalletAggregateRoot::fake()->deposit([
            'note' => 'Salary',
            'amount' => 1000,
        ])->assertRecorded(new MoneyDeposited([
            'note' => 'Salary',
            'amount' => 1000,
        ]));
    }

    public function test_wallet_can_track_expense()
    {
        WalletAggregateRoot::fake()->withdraw([
            'note' => 'Salary',
            'amount' => 1000,
        ])->assertRecorded(new MoneyWithdrawn([
            'note' => 'Salary',
            'amount' => 1000,
        ]));
    }

    public function test_can_transfer_amount_to_other_account()
    {
        $firstUuid = Uuid::uuid4()->toString();
        $secondUuid = Uuid::uuid4()->toString();

        TransferAggregateRoot::fake()->transfer([
            'from_type' => Account::TYPE_WALLET,
            'from_id' => $firstUuid,
            'from_amount' => 400,
            'to_type' => Account::TYPE_WALLET,
            'to_id' => $secondUuid,
            'to_amount' => 500
        ])->assertRecorded([
            new MoneyTransferred([
                'from_type' => Account::TYPE_WALLET,
                'from_id' => $firstUuid,
                'from_amount' => 400,
                'to_type' => Account::TYPE_WALLET,
                'to_id' => $secondUuid,
                'to_amount' => 500
            ])
        ]);

        // TODO what about the rest of the events?
    }
}
