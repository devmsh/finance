<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAbleToSaveException;
use App\Goal;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws NotAbleToSaveException
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->missing('due_date')) {
            $periods = Plan::find(1)->expectedPeriods($request->get('total'));
            $data['due_date'] = Carbon::today()->addMonths($periods);
        }

        return Goal::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Goal $goal
     * @return Response
     */
    public function show(Goal $goal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Goal $goal
     * @return Response
     */
    public function update(Request $request, Goal $goal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Goal $goal
     * @return Response
     */
    public function destroy(Goal $goal)
    {
        //
    }
}
