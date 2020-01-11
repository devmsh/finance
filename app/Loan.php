<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function goal()
    {
        return $this->hasOne(Goal::class);
    }

    public static function record($data)
    {
        /** @var Loan $loan */
        $loan = self::create($data);

        $loan->wallet->addIncome([
            'note' => 'caused by loan',
            'amount' => $loan->total,
            'causedby_id'=> $loan->id,
        ]);

        $loan->goal()->create([
            'name' => 'Pay off loan',
            'total' => $loan->total,
            'due_date' => $loan->payoff_at,
        ]);

        return $loan;
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class,'causedby_id');
    }
}
