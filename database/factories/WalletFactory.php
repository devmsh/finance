<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Wallet;
use Faker\Generator as Faker;

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'user_id' => function(){
            return factory(User::class)->create()->id;
        }
    ];
});
