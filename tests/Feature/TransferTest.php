<?php

namespace Tests\Feature;

use App\Goal;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_transfer_amount_between_wallet_and_goal()
    {
        $this->withoutExceptionHandling();
        // AAAA

        // Arrange
        $wallet = Wallet::open(factory(Wallet::class)->data([
            'initial_balance' => 1000
        ]));

        $goal = factory(Goal::class)->create();

        // Act
        $response = $this->post('api/transfers',[
            'amount' => 400,
            'from_type' => 'wallet',
            'from_id' => $wallet->id,
            'to_type' => 'goal',
            'to_id' => $goal->id,
        ]);

        // Assert
        $response->assertSuccessful();
        $this->assertEquals(600,$wallet->balance());
        $this->assertEquals(400,$goal->balance());
    }
}
