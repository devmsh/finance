<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Category;
use App\Domain\Events\CategoryCreated;
use App\Domain\Events\MoneyDeposited;
use App\Domain\Events\MoneyTransferred;
use App\Domain\Events\MoneyWithdrawn;
use App\Domain\Events\WalletOpened;
use App\Wallet;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class CategoryProjector implements Projector
{
    use ProjectsEvents;

    public function onCategoryCreated(CategoryCreated $event)
    {
        Category::create($event->attributes);
    }
}
