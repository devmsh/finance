<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletAdjustmentRequest;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletAdjustmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Wallet::class);
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'balance' => 'adjustment',
        ]);
    }

    public function balance(Wallet $wallet, WalletAdjustmentRequest $request)
    {
        return $wallet->adjustBalance($request->new_balance);
    }

    public function openBalance(Wallet $wallet, WalletAdjustmentRequest $request)
    {
        return $wallet->adjustOpeningBalance($request->new_balance);
    }
}
