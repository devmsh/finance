<?php

namespace App;

use App\Events\GoalAchieved;
use App\Traits\HasTransactions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon due_date
 */
class Goal extends Model
{
    use HasTransactions;

    protected $guarded = [];

    protected $dates = ['due_date'];

    public function addTransaction($data)
    {
        $transaction = $this->deposit($data);

        if ($this->isAchieved()) {
            event(new GoalAchieved($this));
        }

        return $transaction;
    }

    public function isAchieved()
    {
        return $this->total <= $this->balance();
    }
}
