<?php

namespace App\Bookmark\Application\EventHandler;

use App\Bookmark\Domain\Event\Deleted;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->bookmarkManager->delete($event->getBookmarkId());
    }
}
