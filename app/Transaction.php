<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function causedby()
    {
        return $this->belongsTo(Loan::class);
    }
}
