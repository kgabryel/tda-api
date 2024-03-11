<?php

namespace App\File\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\File\Domain\Event\Deleted;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->fileManager->delete($event->getFileId());
    }
}
