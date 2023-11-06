<?php

namespace App\Note\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Note\Application\Notificator;
use App\Note\Domain\Event\NotesModified;

#[ListenEvent(NotesModified::class)]
class NotesModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(NotesModified $event): void
    {
        $this->notificator->notesChanges($event->getUserId(), ...$event->getIds());
    }
}
