<?php

namespace App\Http\Controllers;

use App\Events\GoalAchieved;
use App\Goal;
use App\Transaction;
use Illuminate\Http\Request;

class GoalTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function index(Goal $goal)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Goal $goal)
    {
        return $goal->addTransaction($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Goal  $goal
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $goal, Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Goal  $goal
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Goal  $goal
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $goal, Transaction $transaction)
    {
        //
    }
}
