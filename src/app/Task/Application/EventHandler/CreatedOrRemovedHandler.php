<?php

namespace App\Task\Application\EventHandler;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\VideoId;
use App\Task\Domain\Event\BookmarksAssigmentChanged;
use App\Task\Domain\Event\CatalogsAssigmentChanged;
use App\Task\Domain\Event\Created;
use App\Task\Domain\Event\FilesAssigmentChanged;
use App\Task\Domain\Event\NotesAssigmentChanged;
use App\Task\Domain\Event\Removed;
use App\Task\Domain\Event\VideosAssigmentChanged;

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
        $catalogs = $event->getTask()->getCatalogsIds();
        $bookmarks = $event->getTask()->getBookmarksIds();
        $files = $event->getTask()->getFilesIds();
        $notes = $event->getTask()->getNotesIds();
        $videos = $event->getTask()->getVideosIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getTask()->getUserId(),
                ...array_map(static fn(int $id) => new CatalogId($id), $catalogs)
            )
        );
        $this->eventEmitter->emit(
            new BookmarksAssigmentChanged(
                $event->getTask()->getUserId(),
                ...array_map(static fn(int $id) => new BookmarkId($id), $bookmarks)
            )
        );
        $this->eventEmitter->emit(
            new FilesAssigmentChanged(
                $event->getTask()->getUserId(),
                ...array_map(static fn(int $id) => new FileId($id), $files)
            )
        );
        $this->eventEmitter->emit(
            new NotesAssigmentChanged(
                $event->getTask()->getUserId(),
                ...array_map(static fn(int $id) => new NoteId($id), $notes)
            )
        );
        $this->eventEmitter->emit(
            new VideosAssigmentChanged(
                $event->getTask()->getUserId(),
                ...array_map(static fn(int $id) => new VideoId($id), $videos)
            )
        );
    }
}
