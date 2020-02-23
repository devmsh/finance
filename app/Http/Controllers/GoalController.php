<?php

namespace App\Http\Controllers;

use App\Goal;
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'total' => 'required',
            'due_date' => 'required|date',
        ]);

        return Goal::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }
}
