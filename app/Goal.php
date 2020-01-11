<?php

namespace App;

use App\Events\GoalAchieved;
use App\Traits\HasTransactions;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasTransactions;

    protected $guarded = [];

    public function addTransaction($data)
    {
        $transaction = $this->transactions()->create($data);

        if ($this->isAchieved()) event(new GoalAchieved($this));

        return $transaction;
    }

    public function isAchieved()
    {
        return $this->total <= $this->balance();
    }
}
