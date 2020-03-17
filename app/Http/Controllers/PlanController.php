<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
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
    public function store(PlanRequest $request)
    {
        return Plan::create($request->validated());
    }

    public function createBudget(Plan $plan, Request $request)
    {
        return $plan->setBudget($request->all());
    }
}
