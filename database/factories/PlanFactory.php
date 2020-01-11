<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'total_income' => 3000,
        'must_have' => 1000,
        'min_saving' => 500,
    ];
});
