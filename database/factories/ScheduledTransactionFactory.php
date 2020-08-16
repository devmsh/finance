<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ScheduledTransaction;
use App\User;
use App\Wallet;
use Faker\Generator as Faker;

$factory->define(ScheduledTransaction::class, function (Faker $faker) {
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
        'rrule' => ScheduledTransaction::rule()
            ->setFreq(ScheduledTransaction::FREQ_MONTHLY)
            ->setByMonthDay([1])->getString(),
    ];
});
