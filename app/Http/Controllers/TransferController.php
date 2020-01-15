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

        $from->transfer($to, $request->amount);

        return [
            'success' => 'amount transferred',
        ];
    }
}
