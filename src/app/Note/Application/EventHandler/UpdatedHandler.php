<?php

namespace App\Note\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Note\Domain\Event\Updated;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->noteManager->update($event->getNote());
    }
}
