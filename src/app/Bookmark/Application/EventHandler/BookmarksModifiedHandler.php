<?php

namespace App\Bookmark\Application\EventHandler;

use App\Bookmark\Application\Notificator;
use App\Bookmark\Domain\Event\BookmarksModified;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(BookmarksModified::class)]
class BookmarksModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(BookmarksModified $event): void
    {
        $this->notificator->bookmarksChanges($event->getUserId(), ...$event->getIds());
    }
}
