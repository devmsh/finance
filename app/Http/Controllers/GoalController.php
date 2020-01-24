<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAbleToSaveException;
use App\Goal;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Goal::specify($data);
    }
}
