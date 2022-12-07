<?php

namespace App\Http\Controllers;

use App\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GoalTransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  Goal  $goal
     * @return Response
     */
    public function store(Request $request, Goal $goal)
    {
        return $goal->addTransaction(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }
}
