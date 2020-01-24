<?php

namespace App;

use App\Domain\GoalAchieved;
use App\Domain\GoalSpecified;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon due_date
 */
class Goal extends Account
{
    protected $guarded = [];

    protected $attributes = [
        'currency' => Currency::USD,
    ];

    protected $dates = ['due_date'];

    public static function specify($attributes)
    {
        event(new GoalSpecified($attributes));
    }

    public function addTransaction($data)
    {
        $this->deposit($data);

        if ($this->isAchieved()) {
            event(new GoalAchieved($this->uuid));
        }
    }

    public function isAchieved()
    {
        return $this->total <= $this->balance();
    }
}
