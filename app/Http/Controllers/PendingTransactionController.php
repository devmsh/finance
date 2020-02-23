<?php

namespace App\Http\Controllers;

use App\PendingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PendingTransactionController extends Controller
{
    public function complete(Request $request, PendingTransaction $pendingTransaction)
    {
        $attributes = array_merge(
            Arr::only($pendingTransaction->toArray(), ['note', 'amount', 'category_id', 'user_id']),
            $request->only(['note', 'amount'])
        );

        $transaction = $pendingTransaction->trackable->deposit($attributes);

        $pendingTransaction->complete();

        return $transaction;
    }
}
