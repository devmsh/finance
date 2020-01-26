<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletIncomeController extends Controller
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
        return $wallet->deposit(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }
}
