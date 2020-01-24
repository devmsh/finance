<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Goal;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->word,
        'total' => $faker->numberBetween(100, 1000),
        'due_date' => \Carbon\Carbon::now()->addYear(),
    ];
});
