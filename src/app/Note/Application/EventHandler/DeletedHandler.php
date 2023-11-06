<?php

namespace App\Note\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Note\Domain\Event\Deleted;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->noteManager->delete($event->getNoteId());
    }
}
