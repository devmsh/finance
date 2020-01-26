<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Budget;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Budget::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return Auth::id() ? Auth::id() : factory(User::class)->create()->id;
        },
    ];
});
