<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Wallet;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'user_id' => function () {
            return Auth::id() ? Auth::id() : factory(User::class)->create()->id;
        },
    ];
});
