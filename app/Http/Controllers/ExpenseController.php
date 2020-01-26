<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExpenseController extends Controller
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function store(Request $request)
    {
        return collect($request->all())->map(function ($expense) {
            $wallet = Wallet::find($expense['wallet_id']);
            unset($expense['wallet_id']);

            return $wallet->withdraw($expense);
        });
    }
}
