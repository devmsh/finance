<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'category_id' => Category::DEFAULT_CATEGORY,
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function causedby()
    {
        return $this->belongsTo(Loan::class);
    }
}
