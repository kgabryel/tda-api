<?php

namespace App\Task\Domain\Entity;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\BookmarksListInterface;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\FilesListInterface;
use App\Shared\Domain\List\NotesListInterface;
use App\Shared\Domain\List\VideosListInterface;
use App\Task\Domain\Exception\AssignedTaskModified;
use App\Task\Domain\Exception\StatusChangeException;
use App\Task\Domain\Exception\TaskUpdateException;
use App\Task\Domain\TaskStatus as StatusName;
use DateTimeImmutable;

class SingleTask extends Task
{
    private TaskId $taskId;
    private ?TasksGroupId $tasksGroupId;
    private ?TaskId $mainTaskId;
    private SubtasksList $subtasks;
    private ?AlarmId $alarmId;
    private TaskStatus $taskStatus;
    private ?DateTimeImmutable $date;

    public function __construct(
        TaskId $taskId,
        ?TasksGroupId $tasksGroupId,
        ?TaskId $mainTaskId,
        SubtasksList $subtasks,
        ?AlarmId $alarmId,
        TaskStatus $taskStatus,
        ?DateTimeImmutable $date,
        UserId $userId,
        string $name,
        ?string $content,
        CatalogsListInterface $catalogsList,
        NotesListInterface $notesList,
        BookmarksListInterface $bookmarksList,
        FilesListInterface $filesList,
        VideosListInterface $videosList
    ) {
        parent::__construct(
            $userId,
            $name,
            $content,
            $catalogsList,
            $notesList,
            $bookmarksList,
            $filesList,
            $videosList
        );
        $this->taskId = $taskId;
        $this->tasksGroupId = $tasksGroupId;
        $this->mainTaskId = $mainTaskId;
        $this->subtasks = $subtasks;
        $this->alarmId = $alarmId;
        $this->taskStatus = $taskStatus;
        $this->date = $date;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function hasMainTask(): bool
    {
        return $this->mainTaskId !== null;
    }

    public function updateDate(?DateTimeImmutable $date): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "date" value, entity was deleted.');
        }
        if ($this->date?->getTimestamp() === $date?->getTimestamp()) {
            return false;
        }
        $this->date = $date;

        return true;
    }

    public function updateAlarm(?AlarmId $alarmId): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "alarm" value, entity was deleted.');
        }
        if ($this->alarmId?->getValue() === $alarmId?->getValue()) {
            return false;
        }
        $this->alarmId = $alarmId;

        return true;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function updateMainTask(?TaskId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "date" value, entity was deleted.');
        }
        if ($this->taskId->getValue() === $id?->getValue()) {
            throw new TaskUpdateException();
        }
        if (!$this->subtasks->isEmpty()) {
            throw new TaskUpdateException();
        }
        if ($this->mainTaskId?->getValue() === $id?->getValue()) {
            return false;
        }
        $this->mainTaskId = $id;

        return true;
    }

    public function updateContent(?string $content): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::updateContent($content);
    }

    public function updateName(string $name): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::updateName($name);
    }

    public function getTaskGroupId(): ?TasksGroupId
    {
        return $this->tasksGroupId;
    }

    public function hasAlarm(): bool
    {
        return $this->alarmId !== null;
    }

    public function getAlarmId(): ?AlarmId
    {
        return $this->alarmId;
    }

    public function setAlarmId(AlarmId $alarmId, bool $fromGroup = false): void
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update "alarm" value, entity was deleted.');
        }

        if ($this->tasksGroupId !== null && !$fromGroup) {
            throw new AssignedTaskModified();
        }

        $this->alarmId = $alarmId;
    }

    public function getMainTaskId(): ?TaskId
    {
        return $this->mainTaskId;
    }

    public function getSubtasksIds(): array
    {
        return $this->subtasks->getIds();
    }

    public function disconnectSubtasks(): void
    {
        $this->subtasks->disconnect();
    }

    public function deleteSubtasks(): void
    {
        $this->subtasks->delete();
    }

    public function removeCatalog(CatalogId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::removeCatalog($id);
    }

    public function addCatalog(CatalogId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::addCatalog($id);
    }

    public function removeBookmark(BookmarkId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::removeBookmark($id);
    }

    public function addBookmark(BookmarkId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::addBookmark($id);
    }

    public function removeNote(NoteId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::removeNote($id);
    }

    public function addNote(NoteId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::addNote($id);
    }

    public function removeFile(FileId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::removeFile($id);
    }

    public function addFile(FileId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::addFile($id);
    }

    public function removeVideo(VideoId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::removeVideo($id);
    }

    public function addVideo(VideoId $id): bool
    {
        if ($this->tasksGroupId !== null) {
            throw new AssignedTaskModified();
        }

        return parent::addVideo($id);
    }

    public function changeStatus(TaskStatus $status): bool|array
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update status, entity was deleted.');
        }
        if ($this->taskStatus->getName() === $status->getName()) {
            return false;
        }
        $subtasks = null;
        if ($status->getName() === StatusName::DONE) {
            $subtasks = $this->subtasks->get();
            /** @var SingleTask $subtask */
            foreach ($subtasks as $subtask) {
                $subtaskStatusName = $subtask->getStatusName();
                if (!($subtaskStatusName === StatusName::DONE || $subtaskStatusName === StatusName::REJECTED)) {
                    throw new StatusChangeException();
                }
            }
        }
        if ($status->getName() === StatusName::UNDONE || $status->getName() === StatusName::REJECTED) {
            $this->subtasks->setStatus($status);
        }
        $this->taskStatus = $status;

        return $subtasks ?? true;
    }

    public function getStatusName(): StatusName
    {
        return $this->taskStatus->getName();
    }

    public function getStatus(): TaskStatus
    {
        return $this->taskStatus;
    }
}
