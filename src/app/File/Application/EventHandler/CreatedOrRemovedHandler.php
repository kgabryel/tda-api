<?php

namespace App\File\Application\EventHandler;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\File\Domain\Event\CatalogsAssigmentChanged;
use App\File\Domain\Event\Created;
use App\File\Domain\Event\Removed;
use App\File\Domain\Event\TasksAssigmentChanged;
use App\File\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;

#[ListenEvent(Created::class)]
#[ListenEvent(Removed::class)]
class CreatedOrRemovedHandler
{
    private EventEmitter $eventEmitter;

    public function __construct(EventEmitter $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    public function handle(Created|Removed $event): void
    {
        $tasks = $event->getFile()->getTasksIds();
        $tasksGroups = $event->getFile()->getTasksGroupsIds();
        $catalogs = $event->getFile()->getCatalogsIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getFile()->getUserId(),
                ...array_map(static fn(int $id) => new CatalogId($id), $catalogs)
            )
        );
        $this->eventEmitter->emit(
            new TasksAssigmentChanged(
                $event->getFile()->getUserId(),
                ...array_map(static fn(string $id) => new TaskId($id), $tasks)
            )
        );
        $this->eventEmitter->emit(
            new TasksGroupsAssigmentChanged(
                $event->getFile()->getUserId(),
                ...array_map(static fn(string $id) => new TasksGroupId($id), $tasksGroups)
            )
        );
    }
}
