<?php

namespace App\Providers;

use App\Events\GoalAchieved;
use App\Events\LoanRecorded;
use App\Listeners\DepositLoanToWallet;
use App\Listeners\PayoffTheLoanGoal;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        GoalAchieved::class => [
        ],
        LoanRecorded::class => [
            DepositLoanToWallet::class,
            PayoffTheLoanGoal::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        User::observe(UserObserver::class);
    }
}
