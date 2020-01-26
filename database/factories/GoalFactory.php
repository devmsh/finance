<?php

/** @var Factory $factory */

use App\Goal;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Auth;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'total' => $faker->numberBetween(100, 1000),
        'due_date' => Carbon::now()->addYear(),
        'user_id' => function(){
            return Auth::id() ?  Auth::id() : factory(User::class)->create()->id;
        },
    ];
});
