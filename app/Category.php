<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const INCOME = 1;
    const EXPENSES = 2;

    const DEFAULT_CATEGORY = 1;

    protected $guarded = [];

    public static function scopeFilter($query, $filters)
    {
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query;
    }

    public static function scopeSort($query, $filters)
    {
        if (isset($filters['sort']) && $filters['sort'] == 'usage') {
            $query->orderByDesc(
                Transaction::selectRaw('count(*) as count')->whereColumn('category_id', 'categories.id')
            );
        }

        return $query;
    }

    public static function scopeDefaultCategories($query)
    {
        $query->where('user_id', null);

        return $query;
    }
}
