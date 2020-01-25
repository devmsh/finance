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
    public function __construct($from_type, $from_id, $from_amount, $to_type, $to_id, $to_amount)
    {
        $this->from_type = $from_type;
        $this->from_id = $from_id;
        $this->to_type = $to_type;
        $this->to_id = $to_id;
        $this->from_amount = $from_amount;
        $this->to_amount = $to_amount;
    }
}
