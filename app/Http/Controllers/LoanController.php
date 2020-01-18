<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        return Loan::create($request->all());
    }
}
