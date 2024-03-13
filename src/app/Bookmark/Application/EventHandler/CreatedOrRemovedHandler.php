<?php

namespace App\Bookmark\Application\EventHandler;

use App\Bookmark\Domain\Event\CatalogsAssigmentChanged;
use App\Bookmark\Domain\Event\Created;
use App\Bookmark\Domain\Event\Removed;
use App\Bookmark\Domain\Event\TasksAssigmentChanged;
use App\Bookmark\Domain\Event\TasksGroupsAssigmentChanged;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
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
        $tasks = $event->getBookmark()->getTasksIds();
        $tasksGroups = $event->getBookmark()->getTasksGroupsIds();
        $catalogs = $event->getBookmark()->getCatalogsIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getBookmark()->getUserId(),
                ...array_map(static fn(int $id) => new CatalogId($id), $catalogs)
            )
        );
        $this->eventEmitter->emit(
            new TasksAssigmentChanged(
                $event->getBookmark()->getUserId(),
                ...array_map(static fn(string $id) => new TaskId($id), $tasks)
            )
        );
        $this->eventEmitter->emit(
            new TasksGroupsAssigmentChanged(
                $event->getBookmark()->getUserId(),
                ...array_map(static fn(string $id) => new TasksGroupId($id), $tasksGroups)
            )
        );
    }
}
