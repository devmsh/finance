<?php

namespace Tests\Unit;

use App\Account;
use App\Domain\WalletAggregateRoot;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class WalletProjectorTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_open_wallet()
    {
        $uuid = Uuid::uuid4()->toString();

        WalletAggregateRoot::retrieve($uuid)->open([
            'uuid' => $uuid,
            'name' => 'Cash',
            'initial_balance' => 1000,
        ])->persist();

        $wallet = Wallet::uuid($uuid);
        $this->assertEquals('Cash', $wallet->name);
    }
}
