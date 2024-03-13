<?php

namespace App\Video\Application\EventHandler;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Video\Domain\Event\CatalogsAssigmentChanged;
use App\Video\Domain\Event\Created;
use App\Video\Domain\Event\Removed;
use App\Video\Domain\Event\TasksAssigmentChanged;
use App\Video\Domain\Event\TasksGroupsAssigmentChanged;

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
        $tasks = $event->getVideo()->getTasksIds();
        $tasksGroups = $event->getVideo()->getTasksGroupsIds();
        $catalogs = $event->getVideo()->getCatalogsIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getVideo()->getUserId(),
                ...array_map(static fn(int $id) => new CatalogId($id), $catalogs)
            )
        );
        $this->eventEmitter->emit(
            new TasksAssigmentChanged(
                $event->getVideo()->getUserId(),
                ...array_map(static fn(string $id) => new TaskId($id), $tasks)
            )
        );
        $this->eventEmitter->emit(
            new TasksGroupsAssigmentChanged(
                $event->getVideo()->getUserId(),
                ...array_map(static fn(string $id) => new TasksGroupId($id), $tasksGroups)
            )
        );
    }
}
