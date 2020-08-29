<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletExpenseIncomeRequest;
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
    public function store(WalletExpenseIncomeRequest $request, Wallet $wallet)
    {
        return $wallet->withdraw(array_merge($request->validated(), [
            'user_id' => Auth::id(),
        ]));
    }
}
