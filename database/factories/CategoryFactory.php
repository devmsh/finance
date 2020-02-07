<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return Auth::id() ? Auth::id() : null;
        },
        'name' => $faker->word,
        'type' => $faker->randomElement([
            Category::INCOME_TYPE,
            Category::EXPENSES_TYPE,
        ]),
    ];
});
