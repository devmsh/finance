<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Wallet::class);
    }

    public function index(Request $request)
    {
        return Auth::user()->wallets()->get();
    }

    public function show(Wallet $wallet, Request $request)
    {
        return $wallet;
    }

    public function store(Request $request)
    {
        return Wallet::open(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }
}
