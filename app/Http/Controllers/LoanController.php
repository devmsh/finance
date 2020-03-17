<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(LoanRequest $request)
    {
        return Loan::create(array_merge($request->validated(), [
            'user_id' => Auth::id(),
        ]));
    }
}
