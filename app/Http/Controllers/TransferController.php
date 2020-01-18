<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // todo need more refactoring
        $from_id = $request->from_id;
        $to_id = $request->to_id;

        $from = ($request->from_type == 'wallet') ?
            Wallet::find($from_id) :
            Goal::find($from_id);

        $to = ($request->to_type == 'wallet') ?
            Wallet::find($to_id) :
            Goal::find($to_id);

        [$to_amount, $from_amount] = $this->singleToManyCurrency($request);

        $from->transfer($to, $from_amount, $to_amount);

        return [
            'success' => 'amount transferred',
        ];
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
        return array($to_amount, $from_amount);
    }
}
