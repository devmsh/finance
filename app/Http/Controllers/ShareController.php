<?php

namespace App\Http\Controllers;

use App\Goal;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function update(Request $request, Wallet $wallet, User $user)
    {
        return $wallet->share($user);
    }

    public function destroy(Request $request, Wallet $wallet, User $user)
    {
        return $wallet->unshare($user);
    }
}
