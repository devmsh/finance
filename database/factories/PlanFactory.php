<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Plan;
use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'uuid' => Uuid::uuid4()->toString(),
        'total_income' => 3000,
        'must_have' => 1000,
        'min_saving' => 500,
    ];
});
