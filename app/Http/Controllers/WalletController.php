<?php

namespace App\Http\Controllers;

use App\Wallet;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class WalletController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function store(Request $request)
    {
        Wallet::open($request->all());
    }
}
