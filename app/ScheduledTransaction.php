<?php

namespace App;

use Carbon\Carbon;
use Recurr\Exception\InvalidRRule;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;
use Recurr\Transformer\Constraint\AfterConstraint;
use Recurr\Transformer\Constraint\BeforeConstraint;
use Recurr\Transformer\Constraint\BetweenConstraint;

class ScheduledTransaction extends Transaction
{
    const FREQ_YEARLY = "YEARLY";
    const FREQ_MONTHLY = "MONTHLY";
    const FREQ_WEEKLY = "WEEKLY";
    const FREQ_DAILY = "DAILY";

    /**
     * @return Rule
     * @throws InvalidRRule
     */
    public static function rule()
    {
        return new Rule();
    }

    public function getMonthlyRecurrences(Carbon $date)
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();

        $transformer = new ArrayTransformer();
        $transformer->setConfig($transformerConfig);

        $betweenConstraint = new BetweenConstraint(
            $date->startOfMonth()->toDateTime(),
            $date->endOfMonth()->toDateTime(),
            true
        );

        return $transformer->transform(new Rule($this->rrule), $betweenConstraint)->map(function($recurrence){
            return Carbon::instance($recurrence->getStart())->startOfDay();
        });
    }
}
