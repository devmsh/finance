<?php

namespace App;

use App\Domain\Events\CategoryCreated;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const INCOME = 1;
    const EXPENSES = 2;

    const DEFAULT_CATEGORY = 1;

    protected $guarded = [];

    public static function createWithAttributes($attributes)
    {
        event(new CategoryCreated($attributes));
    }
}
