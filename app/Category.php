<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Category extends Model
{
    const INCOME_TYPE = 1;
    const EXPENSES_TYPE = 2;
    const TRANSFER_TYPE = 3;

    // todo remove the default category
    const DEFAULT_CATEGORY = 1;

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function scopeFilter($query, $filters)
    {
        if (Arr::exists($filters, 'type')) {
            $query->where('type', $filters['type']);
        }

        return $query;
    }

    public static function scopeSort(Builder $query, $filters)
    {
        if (Arr::get($filters, 'sort') == 'usage') {
            $query->usage();
        }

        return $query;
    }

    public static function scopeUsage(Builder $query)
    {
        return $query->withCount('transactions')->orderByDesc('transactions_count');
    }

    public static function scopeDefaultCategories($query)
    {
        $query->where('user_id', null);

        return $query;
    }
}
