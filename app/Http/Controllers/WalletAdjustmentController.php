<?php

namespace App\Http\Controllers;

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

    public function balance(Wallet $wallet, Request $request)
    {
        return $wallet->adjustBalance($request->new_balance);
    }

    public function openBalance(Wallet $wallet, Request $request)
    {
        return $wallet->adjustOpenBalance($request->new_balance);
    }
}
