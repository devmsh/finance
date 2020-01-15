<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
