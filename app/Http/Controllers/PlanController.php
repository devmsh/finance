<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Plan::create($request->all());
    }

    public function createBudget(Plan $plan, Request $request)
    {
        $plan->setBudget($request->all());

        return $plan->budgets()->get();

    }
}
