<?php

namespace App\Task\Application\EventHandler\SingleTask;

use App\Core\Cqrs\ListenEvent;
use App\Task\Application\EventHandler\EventHandler;
use App\Task\Domain\Event\SingleTask\Updated;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->taskManager->updateSingleTask($event->getTask());
    }
}
