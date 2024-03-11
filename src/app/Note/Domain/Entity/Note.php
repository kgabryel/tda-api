<?php

namespace App\Note\Domain\Entity;

use App\Shared\Application\Dto\Ids;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\List\TasksListInterface;
use App\Shared\Domain\ValueObject\Hex;

class Note
{
    private NoteId $noteId;
    private UserId $userId;
    private string $name;
    private string $content;
    private Hex $backgroundColor;
    private Hex $textColor;
    private bool $assignedToDashboard;
    private CatalogsListInterface $catalogsList;
    private TasksListInterface $tasksList;
    private TasksGroupsListInterface $tasksGroupsList;
    private bool $deleted;

    public function __construct(
        NoteId $noteId,
        UserId $userId,
        string $name,
        string $content,
        Hex $backgroundColor,
        Hex $textColor,
        bool $assignedToDashboard,
        CatalogsListInterface $catalogsList,
        TasksListInterface $tasksList,
        TasksGroupsListInterface $tasksGroupsList
    ) {
        $this->noteId = $noteId;
        $this->userId = $userId;
        $this->name = $name;
        $this->content = $content;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->deleted = false;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
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

    public function updateContent(string $content): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "content" value, entity was deleted.');
        }
        if ($this->content === $content) {
            return false;
        }
        $this->content = $content;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getBackgroundColor(): Hex
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): Hex
    {
        return $this->textColor;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
