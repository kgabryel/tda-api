<?php

namespace App\Bookmark\Application\EventHandler;

use App\Bookmark\Domain\Event\Updated;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->bookmarkManager->update($event->getBookmark());
    }
}
