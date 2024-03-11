<?php

namespace App\Note\Application\EventHandler;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Note\Domain\Event\CatalogsAssigmentChanged;
use App\Note\Domain\Event\Created;
use App\Note\Domain\Event\Removed;
use App\Note\Domain\Event\TasksAssigmentChanged;
use App\Note\Domain\Event\TasksGroupsAssigmentChanged;
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
        $tasks = $event->getNote()->getTasksIds();
        $tasksGroups = $event->getNote()->getTasksGroupsIds();
        $catalogs = $event->getNote()->getCatalogsIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getNote()->getUserId(),
                ...array_map(static fn(string $id) => new CatalogId($id), $catalogs)
            )
        );
        $this->eventEmitter->emit(
            new TasksAssigmentChanged(
                $event->getNote()->getUserId(),
                ...array_map(static fn(string $id) => new TaskId($id), $tasks)
            )
        );
        $this->eventEmitter->emit(
            new TasksGroupsAssigmentChanged(
                $event->getNote()->getUserId(),
                ...array_map(static fn(string $id) => new TasksGroupId($id), $tasksGroups)
            )
        );
    }
}
