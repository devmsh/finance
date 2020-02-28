<?php

namespace Tests\Unit;

use App\Http\Requests\DailyExpenseRequest;
use App\Http\Requests\WalletExpenseIncomeRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_validation_rules_on_daily_expenses()
    {
        $this->assertEquals([
            '*.note' => 'required',
            '*.amount' => 'required',
            '*.wallet_id' => 'required',
            '*.category_id' => 'required',
        ], (new DailyExpenseRequest())->rules());
    }

    public function test_test_validation_rules_on_wallet_expenses_and_income()
    {
        $this->assertEquals([
            'note' => 'required',
            'amount' => 'required',
            'category_id' => 'required',
        ], (new WalletExpenseIncomeRequest())->rules());
    }
}
