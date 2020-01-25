<?php

namespace App;

use App\Domain\Events\LoanRecorded;

class Loan extends Model
{
    protected $guarded = [];

    protected $appends = [
        'currency',
    ];

    protected $dates = [
        'payoff_at',
    ];

    public static function record($attributes)
    {
        event(new LoanRecorded($attributes));
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function goal()
    {
        return $this->hasOne(Goal::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'causedby_id');
    }

    public function getCurrencyAttribute()
    {
        return $this->wallet->currency;
    }
}
