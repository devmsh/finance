<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wallet;
use Faker\Generator as Faker;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'initial_balance' => $faker->numberBetween(100,1000)
    ];
});
