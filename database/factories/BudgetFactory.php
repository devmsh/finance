<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Budget;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Budget::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
