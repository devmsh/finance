<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return Plan::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
        ]));
    }

    public function createBudget(Plan $plan, Request $request)
    {
        $plan->setBudget($request->all());

        return $plan->budgets()->get();
    }
}
