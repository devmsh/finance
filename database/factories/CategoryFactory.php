<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'type' => $faker->randomElement([
            Category::INCOME_TYPE,
            Category::EXPENSES_TYPE,
        ])
    ];
});
