<?php

namespace App\Bookmark\Domain\Entity;

use App\Shared\Application\Dto\Ids;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\List\TasksListInterface;
use App\Shared\Domain\ValueObject\Hex;

class Bookmark
{
    private BookmarkId $bookmarkId;
    private UserId $userId;
    private string $name;
    private string $href;
    private ?string $icon;
    private bool $assignedToDashboard;
    private Hex $backgroundColor;
    private Hex $textColor;
    private CatalogsListInterface $catalogsList;
    private TasksListInterface $tasksList;
    private TasksGroupsListInterface $tasksGroupsList;
    private bool $deleted;

    public function __construct(
        BookmarkId $bookmarkId,
        UserId $userId,
        string $name,
        string $href,
        ?string $icon,
        bool $assignedToDashboard,
        Hex $backgroundColor,
        Hex $textColor,
        CatalogsListInterface $catalogsList,
        TasksListInterface $tasksList,
        TasksGroupsListInterface $tasksGroupsList
    ) {
        $this->bookmarkId = $bookmarkId;
        $this->userId = $userId;
        $this->name = $name;
        $this->href = $href;
        $this->icon = $icon;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->deleted = false;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
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

    public function updateHref(string $href): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "href" value, entity was deleted.');
        }
        if ($this->href === $href) {
            return false;
        }
        $this->href = $href;

        return true;
    }

    public function updateIcon(?string $icon): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "icon" value, entity was deleted.');
        }
        if ($icon === '') {
            $icon = null;
        }
        if ($this->icon === $icon) {
            return false;
        }
        $this->icon = $icon;

        return true;
    }

    public function updateTextColor(Hex $color): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "textColor" value, entity was deleted.');
        }
        if ($this->textColor->isSame($color)) {
            return false;
        }
        $this->textColor = $color;

        return true;
    }

    public function updateBackgroundColor(Hex $color): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "backgroundColor" value, entity was deleted.');
        }
        if ($this->backgroundColor->isSame($color)) {
            return false;
        }
        $this->backgroundColor = $color;

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

    public function getBackgroundColor(): Hex
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): Hex
    {
        return $this->textColor;
    }
}
