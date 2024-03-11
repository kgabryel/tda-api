<?php

namespace App\Task\Application\EventHandler\PeriodicTask;

use App\Core\Cqrs\ListenEvent;
use App\Task\Application\EventHandler\EventHandler;
use App\Task\Domain\Event\PeriodicTask\Deleted;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->taskManager->deletePeriodicTask($event->getTaskId());
    }
}
