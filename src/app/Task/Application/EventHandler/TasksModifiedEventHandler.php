<?php

namespace App\Task\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Task\Application\Notificator;
use App\Task\Domain\Event\Port\TasksModified;

#[ListenEvent(TasksModified::class)]
class TasksModifiedEventHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(TasksModified $event): void
    {
        $this->notificator->tasksChanges($event->getUserId(), ...$event->getIds());
    }
}
