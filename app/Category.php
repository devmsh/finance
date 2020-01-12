<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const INCOME = 1;
    const EXPENSES = 2;

    const DEFAULT_CATEGORY = 1;

    protected $guarded = [];
}
