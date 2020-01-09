<?php

namespace App;

use App\Events\GoalAchieved;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    public function transcations()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addTransaction($data)
    {
        $transaction = $this->transcations()->create($data);

        if ($this->isAchieved()) event(new GoalAchieved($this));

        return $transaction;
    }

    public function isAchieved()
    {
        return $this->total <= $this->trackedAmount();
    }

    private function trackedAmount()
    {
        return $this->transcations()->sum('amount');
    }

}
