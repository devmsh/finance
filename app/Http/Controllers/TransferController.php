<?php

namespace App\Http\Controllers;

use App\Account;
use App\Goal;
use App\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        [$to_amount, $from_amount] = $this->singleToManyCurrency($request);

        Account::transfer(
            $request->from_type, $request->from_id, $from_amount,
            $request->to_type, $request->to_id, $to_amount
        );
    }

    /**
     * @deprecated
     * @param Request $request
     * @return array
     */
    private function singleToManyCurrency(Request $request): array
    {
        if ($request->amount) {
            $from_amount = $to_amount = $request->amount;
        } else {
            $from_amount = $request->from_amount;
            $to_amount = $request->to_amount;
        }

        return [$to_amount, $from_amount];
    }
}
