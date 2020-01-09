<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Goal;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
        'amount' => $faker->numberBetween(1,500),
        'goal_id' => function(){
            return factory(Goal::class)->create()->id;
        }
    ];
});
