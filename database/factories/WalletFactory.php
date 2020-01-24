<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wallet;
use Faker\Generator as Faker;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->word,
    ];
});
