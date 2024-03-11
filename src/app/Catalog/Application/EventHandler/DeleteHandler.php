<?php

namespace App\Catalog\Application\EventHandler;

use App\Catalog\Domain\Event\Deleted;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Deleted::class)]
class DeleteHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->catalogManager->delete($event->getCatalogId());
    }
}
