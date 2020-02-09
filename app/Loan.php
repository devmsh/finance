<?php

namespace App;

use App\Events\LoanRecorded;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => LoanRecorded::class,
    ];

    protected $dates = [
        'payoff_at'
    ];

    protected $appends = [
        'currency',
    ];

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
