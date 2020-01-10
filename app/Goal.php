<?php

namespace App;

use App\Events\GoalAchieved;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addTransaction($data)
    {
        $transaction = $this->transactions()->create($data);

        if ($this->isAchieved()) event(new GoalAchieved($this));

        return $transaction;
    }

    public function isAchieved()
    {
        return $this->total <= $this->trackedAmount();
    }

    public function trackedAmount()
    {
        return $this->transactions()->sum('amount');
    }

}
