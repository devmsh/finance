<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Goal;
use App\Transaction;
use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'note' => $faker->sentence,
        'amount' => $faker->numberBetween(1, 500),
    ];
});
