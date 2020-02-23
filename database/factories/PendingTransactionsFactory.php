<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PendingTransaction;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(PendingTransaction::class, function (Faker $faker) {
    return [
        'note' => $faker->sentence,
        'amount' => $faker->numberBetween(1, 500),
        'trackable_type' => Wallet::class,
        'trackable_id' => function () {
            return factory(Wallet::class)->create()->id;
        },
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'due_date' => Carbon::today()
    ];
});
