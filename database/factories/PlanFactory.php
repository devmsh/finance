<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Plan;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'total_income' => 3000,
        'must_have' => 1000,
        'min_saving' => 500,
        'user_id' => function () {
            return Auth::id() ? Auth::id() : factory(User::class)->create()->id;
        },
    ];
});
