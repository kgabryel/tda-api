<?php

namespace App\Video\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Video\Domain\Event\Updated;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->videoManager->update($event->getVideo());
    }
}
