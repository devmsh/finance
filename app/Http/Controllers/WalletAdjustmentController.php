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
        if (auth()->user()->id != $wallet->user_id){
            abort(403);
        }
//         abort_if(auth()->user()->isNot($wallet->user_id), 403);
        return $wallet->adjustBalance($request->new_balance);
    }

    public function openBalance(Wallet $wallet, Request $request)
    {
        return $wallet->adjustOpenBalance($request->new_balance);
    }
}
