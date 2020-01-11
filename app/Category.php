<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const INCOME = 1;
    const EXPENSES = 2;

    protected $guarded = [];
}
