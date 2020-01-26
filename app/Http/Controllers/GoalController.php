<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAbleToSaveException;
use App\Goal;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws NotAbleToSaveException
     */
    public function store(Request $request)
    {
        return Goal::create(array_merge($request->all(),[
            'user_id' => Auth::id()
        ]));
    }
}
