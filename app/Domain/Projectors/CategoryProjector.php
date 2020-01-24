<?php

namespace App\Domain\Projectors;

use App\Account;
use App\Category;
use App\Domain\CategoryCreated;
use App\Domain\MoneyDeposited;
use App\Domain\MoneyTransferred;
use App\Domain\MoneyWithdrawn;
use App\Domain\WalletOpened;
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
