<?php

namespace App\Catalog\Domain\Entity;

use App\Shared\Application\Dto\Ids;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\AlarmsGroupsListInterface;
use App\Shared\Domain\List\AlarmsListInterface;
use App\Shared\Domain\List\BookmarksListInterface;
use App\Shared\Domain\List\FilesListInterface;
use App\Shared\Domain\List\NotesListInterface;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\List\TasksListInterface;
use App\Shared\Domain\List\VideosListInterface;

class Catalog
{
    private CatalogId $catalogId;
    private UserId $userId;
    private string $name;
    private bool $assignedToDashboard;
    private TasksListInterface $tasksList;
    private TasksGroupsListInterface $tasksGroupsList;
    private NotesListInterface $notesList;
    private BookmarksListInterface $bookmarksList;
    private FilesListInterface $filesList;
    private VideosListInterface $videosList;
    private AlarmsListInterface $alarmsList;
    private AlarmsGroupsListInterface $alarmsGroupsList;
    private bool $deleted;

    public function __construct(
        CatalogId $catalogId,
        UserId $userId,
        string $name,
        bool $assignedToDashboard,
        TasksListInterface $tasksList,
        TasksGroupsListInterface $tasksGroupsList,
        NotesListInterface $notesList,
        BookmarksListInterface $bookmarksList,
        FilesListInterface $filesList,
        VideosListInterface $videosList,
        AlarmsListInterface $alarmsList,
        AlarmsGroupsListInterface $alarmsGroupsList
    ) {
        $this->catalogId = $catalogId;
        $this->userId = $userId;
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->notesList = $notesList;
        $this->bookmarksList = $bookmarksList;
        $this->filesList = $filesList;
        $this->videosList = $videosList;
        $this->alarmsList = $alarmsList;
        $this->alarmsGroupsList = $alarmsGroupsList;
        $this->deleted = false;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getTasksIds(): array
    {
        return $this->tasksList->getIds();
    }

    public function getTasksGroupsIds(): array
    {
        return $this->tasksGroupsList->getIds();
    }

    public function getAlarmsIds(): array
    {
        return $this->alarmsList->getIds();
    }

    public function getAlarmsGroupsIds(): array
    {
        return $this->alarmsGroupsList->getIds();
    }

    public function getBookmarksIds(): array
    {
        return $this->bookmarksList->getIds();
    }

    public function getNotesIds(): array
    {
        return $this->notesList->getIds();
    }

    public function getFilesIds(): array
    {
        return $this->filesList->getIds();
    }

    public function getVideosIds(): array
    {
        return $this->videosList->getIds();
    }

    public function updateName(string $name): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "name" value, entity was deleted.');
        }
        if ($this->name === $name) {
            return false;
        }
        $this->name = $name;

        return true;
    }

    public function updateAssignedToDashboard(bool $assigned): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "assignedToDashboard" value, entity was deleted.');
        }
        if ($this->assignedToDashboard === $assigned) {
            return false;
        }
        $this->assignedToDashboard = $assigned;

        return true;
    }

    public function updateBookmarks(BookmarkId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update bookmarks, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(int $id) => new BookmarkId($id), $this->bookmarksList->sync(...$ids)->getAll())
        );
    }

    public function removeBookmark(BookmarkId $bookmarkId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update bookmarks, entity was deleted.');
        }

        return $this->bookmarksList->detach($bookmarkId);
    }

    public function removeFile(FileId $fileId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update files, entity was deleted.');
        }

        return $this->filesList->detach($fileId);
    }

    public function removeNote(NoteId $noteId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update notes, entity was deleted.');
        }

        return $this->notesList->detach($noteId);
    }

    public function removeVideo(VideoId $videoId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update videos, entity was deleted.');
        }

        return $this->videosList->detach($videoId);
    }

    public function removeTask(TaskId $taskId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update tasks, entity was deleted.');
        }

        return $this->tasksList->detach($taskId);
    }

    public function removeTasksGroup(TasksGroupId $tasksGroupId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update tasks, entity was deleted.');
        }

        return $this->tasksGroupsList->detach($tasksGroupId);
    }

    public function removeAlarm(AlarmId $alarmId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update alarms, entity was deleted.');
        }

        return $this->alarmsList->detach($alarmId);
    }

    public function removeAlarmsGroup(AlarmsGroupId $alarmsGroupId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update alarms, entity was deleted.');
        }

        return $this->alarmsGroupsList->detach($alarmsGroupId);
    }

    public function updateFiles(FileId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update files, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(int $id) => new FileId($id), $this->filesList->sync(...$ids)->getAll())
        );
    }

    public function updateNotes(NoteId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update notes, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(int $id) => new NoteId($id), $this->notesList->sync(...$ids)->getAll())
        );
    }

    public function updateVideos(VideoId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update videos, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(int $id) => new VideoId($id), $this->videosList->sync(...$ids)->getAll())
        );
    }

    public function updateAlarms(AlarmId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update alarms, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(string $id) => new AlarmId($id), $this->alarmsList->sync(...$ids)->getAll())
        );
    }

    public function updateAlarmsGroups(AlarmsGroupId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update alarms, entity was deleted.');
        }

        return new Ids(
            ...array_map(
                static fn(string $id) => new AlarmsGroupId($id),
                $this->alarmsGroupsList->sync(...$ids)->getAll()
            )
        );
    }

    public function updateTasks(TaskId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update tasks, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(string $id) => new TaskId($id), $this->tasksList->sync(...$ids)->getAll())
        );
    }

    public function updateTasksGroups(TasksGroupId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update tasks, entity was deleted.');
        }

        return new Ids(
            ...array_map(
                static fn(string $id) => new TasksGroupId($id),
                $this->tasksGroupsList->sync(...$ids)->getAll()
            )
        );
    }

    public function delete(): bool
    {
        if ($this->deleted) {
            return false;
        }
        $this->deleted = true;

        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
