<?php

namespace App\Domain\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class MoneyTransferred implements ShouldBeStored
{
    public $from_type;
    public $from_id;
    public $to_type;
    public $to_id;
    public $from_amount;
    public $to_amount;

    /**
     * MoneyTransferred constructor.
     */
    public function __construct($attributes)
    {
        $this->from_type = $attributes['from_type'];
        $this->from_id = $attributes['from_id'];
        $this->to_type = $attributes['to_type'];
        $this->to_id = $attributes['to_id'];
        $this->from_amount = $attributes['from_amount'];
        $this->to_amount = $attributes['to_amount'];
    }
}
