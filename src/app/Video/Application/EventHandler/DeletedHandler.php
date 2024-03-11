<?php

namespace App\Video\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Video\Domain\Event\Deleted;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->videoManager->delete($event->getVideoId());
    }
}
