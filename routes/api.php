<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->group(function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('transfers', 'TransferController');
    Route::resource('loans', 'LoanController');
    Route::resource('plans', 'PlanController');
    Route::post('plans/{plan}/budget', 'PlanController@createBudget');
    Route::resource('goals', 'GoalController');
    Route::resource('wallets', 'WalletController');
    Route::post('wallets/{wallet}/balance', 'WalletAdjustmentController@balance');
    Route::post('wallets/{wallet}/openBalance', 'WalletAdjustmentController@openBalance');
    Route::resource('goals.transactions', 'GoalTransactionController');
    Route::resource('wallets.income', 'WalletIncomeController');
    Route::resource('wallets.users', 'ShareController');
    Route::resource('wallets.expenses', 'WalletExpenseController');
    Route::resource('expenses', 'ExpenseController');
});
