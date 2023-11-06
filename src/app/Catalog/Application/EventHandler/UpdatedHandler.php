<?php

namespace App\Catalog\Application\EventHandler;

use App\Catalog\Domain\Event\Updated;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->catalogManager->update($event->getCatalog());
    }
}
