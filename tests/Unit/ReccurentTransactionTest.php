<?php

namespace Tests\Unit;

use App\Jobs\GenerateMonthlyTransactions;
use App\PendingTransaction;
use App\ScheduledTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReccurentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_next_schedule_transactions_at_a_month()
    {
        $rrule = ScheduledTransaction::rule()
            ->setFreq(ScheduledTransaction::FREQ_MONTHLY)
            ->setByMonthDay([1]);

        /** @var ScheduledTransaction $transaction */
        $transaction = factory(ScheduledTransaction::class)->create([
            'rrule' => $rrule->getString(),
        ]);

        $recurrences = $transaction->getMonthlyRecurrences(Carbon::parse('2020-03-01'));

        $this->assertCount(1, $recurrences);
        $this->assertEquals(new \DateTime('2020-03-01'), $recurrences->first());
    }

    public function test_can_generate_monthly_checklist_for_the_scheduled_transactions()
    {
        $user = factory(User::class)->create();

        /** @var ScheduledTransaction $transaction */
        factory(ScheduledTransaction::class)->attachTo([
            'rrule' => ScheduledTransaction::rule()
                ->setFreq(ScheduledTransaction::FREQ_MONTHLY)
                ->setByMonthDay([3, 15])->getString(),
        ], $user);

        Carbon::setTestNow(Carbon::today()->addMonth());
        GenerateMonthlyTransactions::dispatch($user);

        $this->assertCount(2, $user->pendingTransactions);
        /** @var PendingTransaction $pendingTransaction */
        $this->assertEquals(3, $user->pendingTransactions[0]->due_date->day);
        $this->assertEquals(PendingTransaction::STATUS_PENDING, $user->pendingTransactions[0]->status);
        $this->assertEquals(15, $user->pendingTransactions[1]->due_date->day);
        $this->assertEquals(PendingTransaction::STATUS_PENDING, $user->pendingTransactions[1]->status);
    }
}
