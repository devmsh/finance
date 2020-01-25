<?php

namespace App;

class Wallet extends Account
{
    protected $guarded = [];

    protected $attributes = [
        'currency' => Currency::USD,
    ];
}
