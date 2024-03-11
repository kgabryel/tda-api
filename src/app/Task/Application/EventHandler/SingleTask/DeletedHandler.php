<?php

namespace App\Task\Application\EventHandler\SingleTask;

use App\Core\Cqrs\ListenEvent;
use App\Task\Application\EventHandler\EventHandler;
use App\Task\Domain\Event\SingleTask\Deleted;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->taskManager->deleteSingleTask($event->getTaskId());
    }
}
