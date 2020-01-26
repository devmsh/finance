<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Goal;
use App\User;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'total' => $faker->numberBetween(100, 1000),
        'due_date' => \Carbon\Carbon::now()->addYear(),
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
    ];
});
