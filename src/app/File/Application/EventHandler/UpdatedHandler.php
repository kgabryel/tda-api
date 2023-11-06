<?php

namespace App\File\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\File\Domain\Event\Updated;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->fileManager->update($event->getFile());
    }
}
