<?php

namespace App\Http\Controllers;

use App\Events\GoalAchieved;
use App\Goal;
use App\Transaction;
use Illuminate\Http\Request;

class GoalTransactionController extends Controller
{
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
}
