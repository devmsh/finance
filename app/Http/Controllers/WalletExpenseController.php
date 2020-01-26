<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletExpenseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Wallet $wallet
     * @return Response
     */
    public function store(Request $request, Wallet $wallet)
    {
        return $wallet->withdraw(array_merge($request->all(), [
            'user_id' => Auth::id()
        ]));
    }
}
