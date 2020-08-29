<?php

namespace Tests\Unit;

use App\Http\Controllers\WalletAdjustmentController;
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

    public function test_wallet_adjustment_actions_uses_form_request()
    {
        $this->assertActionUsesFormRequest(
            WalletAdjustmentController::class,
            'balance',
            WalletAdjustmentRequest::class
        );

        $this->assertActionUsesFormRequest(
            WalletAdjustmentController::class,
            'openBalance',
            WalletAdjustmentRequest::class
        );
    }
}
