<?php

namespace App\Video\Domain\Entity;

use App\Shared\Application\Dto\Ids;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\List\TasksListInterface;

class Video
{
    private VideoId $videoId;
    private UserId $userId;
    private string $name;
    private bool $assignedToDashboard;
    private bool $isWatched;
    private CatalogsListInterface $catalogsList;
    private TasksListInterface $tasksList;
    private TasksGroupsListInterface $tasksGroupsList;
    private bool $deleted;

    public function __construct(
        VideoId $videoId,
        UserId $userId,
        string $name,
        bool $assignedToDashboard,
        bool $isWatched,
        CatalogsListInterface $catalogsList,
        TasksListInterface $tasksList,
        TasksGroupsListInterface $tasksGroupsList
    ) {
        $this->videoId = $videoId;
        $this->userId = $userId;
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->isWatched = $isWatched;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->deleted = false;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
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

    public function markAsWatched(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "watched" value, entity was deleted.');
        }
        if ($this->isWatched) {
            return false;
        }
        $this->isWatched = true;

        return true;
    }

    public function changeName(string $name): bool
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

    public function markAsUnwatched(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "watched" value, entity was deleted.');
        }
        if (!$this->isWatched) {
            return false;
        }
        $this->isWatched = false;

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

    public function isWatched(): bool
    {
        return $this->isWatched;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
