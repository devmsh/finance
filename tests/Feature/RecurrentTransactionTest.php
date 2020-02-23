<?php

namespace Tests\Feature;

use App\Category;
use App\Jobs\GenerateMonthlyTransactions;
use App\PendingTransaction;
use App\ScheduledTransaction;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecurrentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_schedule_monthly_transaction()
    {
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->attachTo([], $user);

        $category = factory(Category::class)->attachTo([
            'type' => Category::INCOME_TYPE,
        ], $user);

        $rrule = ScheduledTransaction::rule()->setFreq(ScheduledTransaction::FREQ_MONTHLY);

        $this->passportAs($user)
            ->post("api/wallets/{$wallet->id}/schedule", [
                'note' => 'Salary',
                'amount' => 1000,
                'category_id' => $category->id,
                'rrule' => $rrule->getString(),
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Salary',
                'amount' => 1000,
                'user_id' => $user->id,
                'category_id' => $category->id,
                'trackable_id' => $wallet->id,
                'rrule' => $rrule->getString(),
            ]);
    }

    public function test_can_mark_pending_transaction_as_completed()
    {
        $user = factory(User::class)->create();

        /** @var PendingTransaction $pendingTransaction */
        $pendingTransaction = factory(PendingTransaction::class)->attachTo([],$user);

        $this->passportAs($user)
            ->post("api/pendingTransaction/{$pendingTransaction->id}/complete")
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => $pendingTransaction->note,
                'amount' => $pendingTransaction->amount,
                'user_id' => $pendingTransaction->user_id,
                'category_id' => $pendingTransaction->category_id,
                'trackable_id' => $pendingTransaction->trackable_id,
            ]);

        $this->assertTrue($pendingTransaction->fresh()->isCompleted());
    }

    public function test_can_mark_pending_transaction_as_completed_with_custom_data()
    {
        $user = factory(User::class)->create();

        $pendingTransaction = factory(PendingTransaction::class)->attachTo([],$user);

        $this->withoutExceptionHandling()->passportAs($user)
            ->post("api/pendingTransaction/{$pendingTransaction->id}/complete", [
                'note' => 'Salary',
                'amount' => 200,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'Salary',
                'amount' => 200,
                'user_id' => $pendingTransaction->user_id,
                'category_id' => $pendingTransaction->category_id,
                'trackable_id' => $pendingTransaction->trackable_id,
            ]);
    }
}
