<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    // Todo remove the default category or get the default by user.
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
