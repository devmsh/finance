<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Loan;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'wallet_id' => function () {
            return factory(\App\Wallet::class)->create()->id;
        },
        'total' => $faker->numberBetween(100, 1000),
        'payoff_at' => \Carbon\Carbon::today()->addYear(),
    ];
});
