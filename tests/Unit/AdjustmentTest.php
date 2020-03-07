<?php

namespace Tests\Unit;

use App\Http\Requests\WalletAdjustmentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdjustmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_rules_on_wallet_balance_adjustment()
    {
        $this->assertEquals([
            'new_balance' => 'required|numeric',
        ], (new WalletAdjustmentRequest())->rules());
    }
}
