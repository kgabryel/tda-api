<?php

namespace App\File\Domain\Entity;

use App\File\Domain\FileData;
use App\Shared\Application\Dto\Ids;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\List\TasksListInterface;

class File
{
    public const STORAGE_DIRECTORY = 'files';
    private FileId $fileId;
    private UserId $userId;
    private string $name;
    private bool $assignedToDashboard;
    private CatalogsListInterface $catalogsList;
    private TasksListInterface $tasksList;
    private TasksGroupsListInterface $tasksGroupsList;
    private FileData $fileData;
    private bool $deleted;

    public function __construct(
        FileId $fileId,
        UserId $userId,
        string $name,
        bool $assignedToDashboard,
        FileData $fileData,
        CatalogsListInterface $catalogsList,
        TasksListInterface $tasksList,
        TasksGroupsListInterface $tasksGroupsList
    ) {
        $this->fileId = $fileId;
        $this->userId = $userId;
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->fileData = $fileData;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->deleted = false;
    }

    public function getMimeType(): string
    {
        return $this->fileData->getMimeType();
    }

    public function getOriginalName(): string
    {
        return $this->fileData->getOriginalName();
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getCatalogsIds(): array
    {
        return $this->catalogsList->getIds();
    }

    public function getTasksIds(): array
    {
        return $this->tasksList->getIds();
    }

    public function getTasksGroupsIds(): array
    {
        return $this->tasksGroupsList->getIds();
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

    public function updateCatalogs(CatalogId ...$ids): Ids
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot update catalogs, entity was deleted.');
        }

        return new Ids(
            ...array_map(static fn(int $id) => new CatalogId($id), $this->catalogsList->sync(...$ids)->getAll())
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

    public function replaceFile(FileData $fileData): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot replace file, entity was deleted.');
        }
        $this->fileData = $fileData;

        return true;
    }

    public function getFullPath(): string
    {
        return sprintf('%s%s%s', self::STORAGE_DIRECTORY, DIRECTORY_SEPARATOR, $this->getPath());
    }

    public function getPath(): string
    {
        return $this->fileData->getPath();
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
