<?php

namespace App\Http\Controllers;

use App\Domain\WalletAggregateRoot;
use App\Wallet;
use Illuminate\Http\Request;

class WalletIncomeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Wallet $wallet
     * @param Request $request
     * @return void
     */
    public function store(Wallet $wallet, Request $request)
    {
        WalletAggregateRoot::retrieve($wallet->uuid)
            ->deposit($request->all())
            ->persist();
    }
}
