<?php

namespace App\Task\Application\EventHandler\PeriodicTask;

use App\Core\Cqrs\ListenEvent;
use App\Task\Application\EventHandler\EventHandler;
use App\Task\Domain\Event\PeriodicTask\Updated;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->taskManager->updatePeriodicTask($event->getTask());
    }
}
