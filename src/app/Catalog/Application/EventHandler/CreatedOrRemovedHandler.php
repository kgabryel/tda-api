<?php

namespace App\Catalog\Application\EventHandler;

use App\Catalog\Domain\Event\AlarmsAssigmentChanged;
use App\Catalog\Domain\Event\AlarmsGroupsAssigmentChanged;
use App\Catalog\Domain\Event\BookmarksAssigmentChanged;
use App\Catalog\Domain\Event\Created;
use App\Catalog\Domain\Event\FilesAssigmentChanged;
use App\Catalog\Domain\Event\NotesAssigmentChanged;
use App\Catalog\Domain\Event\Removed;
use App\Catalog\Domain\Event\TasksAssigmentChanged;
use App\Catalog\Domain\Event\TasksGroupsAssigmentChanged;
use App\Catalog\Domain\Event\VideosAssigmentChanged;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\VideoId;

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
        $tasks = $event->getCatalog()->getTasksIds();
        $tasksGroups = $event->getCatalog()->getTasksGroupsIds();
        $alarms = $event->getCatalog()->getAlarmsIds();
        $alarmsGroups = $event->getCatalog()->getAlarmsGroupsIds();
        $bookmarks = $event->getCatalog()->getBookmarksIds();
        $files = $event->getCatalog()->getFilesIds();
        $notes = $event->getCatalog()->getNotesIds();
        $videos = $event->getCatalog()->getVideosIds();
        $this->eventEmitter->emit(
            new TasksAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(string $id) => new TaskId($id), $tasks)
            )
        );
        $this->eventEmitter->emit(
            new TasksGroupsAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(string $id) => new TasksGroupId($id), $tasksGroups)
            )
        );
        $this->eventEmitter->emit(
            new BookmarksAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(int $id) => new BookmarkId($id), $bookmarks)
            )
        );
        $this->eventEmitter->emit(
            new FilesAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(int $id) => new FileId($id), $files)
            )
        );
        $this->eventEmitter->emit(
            new NotesAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(int $id) => new NoteId($id), $notes)
            )
        );
        $this->eventEmitter->emit(
            new VideosAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(int $id) => new VideoId($id), $videos)
            )
        );
        $this->eventEmitter->emit(
            new AlarmsAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(string $id) => new AlarmId($id), $alarms)
            )
        );
        $this->eventEmitter->emit(
            new AlarmsGroupsAssigmentChanged(
                $event->getCatalog()->getUserId(),
                ...array_map(static fn(string $id) => new AlarmsGroupId($id), $alarmsGroups)
            )
        );
    }
}
