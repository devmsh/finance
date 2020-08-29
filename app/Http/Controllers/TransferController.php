<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Http\Requests\TransferRequest;
use App\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransferController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(TransferRequest $request)
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

        return response()->json([
            'new_from_amount' => $from->balance(),
            'new_to_amount' => $to->balance(),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @deprecated
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
