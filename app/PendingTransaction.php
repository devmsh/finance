<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class PendingTransaction extends Transaction
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_CANCELED = 2;

    protected $dates = [
        'due_date'
    ];

    /**
     * @param ScheduledTransaction $scheduledTransaction
     */
    public static function generateMonthlyTransactions($scheduledTransaction)
    {
        foreach ($scheduledTransaction->getMonthlyRecurrences(Carbon::today()) as $recurrence) {
            $attributes = Arr::except($scheduledTransaction->toArray(), ['id', 'rrule']);
            $attributes['due_date'] = $recurrence;
            PendingTransaction::create($attributes);
        }
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_CANCELED;
    }

    public function complete()
    {
        return $this->update([
            'status' => self::STATUS_CANCELED
        ]);
    }
}
