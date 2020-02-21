<?php

namespace App\Http\Controllers;

use App\ScheduledTransaction;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function store(Request $request, Wallet $wallet)
    {
        return $wallet->schedule(array_merge($request->all(),[
            'user_id' => Auth::id()
        ]));
    }
}
