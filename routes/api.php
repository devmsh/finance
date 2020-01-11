<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('loans','LoanController');
Route::resource('plans','PlanController');
Route::resource('goals','GoalController');
Route::resource('wallets','WalletController');
Route::resource('goals.transactions','GoalTransactionController');
Route::resource('wallets.income','WalletIncomeController');
Route::resource('wallets.expenses','WalletExpenseController');
