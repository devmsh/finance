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

    public function balance(Wallet $wallet, Request $request)
    {
        $this->authorize('balanceAdjustment', $wallet);
        return $wallet->adjustBalance($request->new_balance);
    }

    public function openBalance(Wallet $wallet, Request $request)
    {
        return $wallet->adjustOpenBalance($request->new_balance);
    }
}
