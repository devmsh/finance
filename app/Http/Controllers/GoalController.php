<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Http\Requests\GoalRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(GoalRequest $request)
    {
        return Goal::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }
}
