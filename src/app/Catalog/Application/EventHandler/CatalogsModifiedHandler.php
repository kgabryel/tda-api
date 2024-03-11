<?php

namespace App\Catalog\Application\EventHandler;

use App\Catalog\Application\Notificator;
use App\Catalog\Domain\Event\CatalogsModified;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(CatalogsModified::class)]
class CatalogsModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(CatalogsModified $event): void
    {
        $this->notificator->catalogsChanges($event->getUserId(), ...$event->getIds());
    }
}
