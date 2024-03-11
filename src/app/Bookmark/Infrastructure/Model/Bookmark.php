<?php

namespace App\Bookmark\Infrastructure\Model;

use App\Bookmark\Application\ViewModel\Bookmark as ViewModel;
use App\Bookmark\Domain\Entity\Bookmark as DomainModel;
use App\Catalog\Infrastructure\Model\Catalog;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;
use App\Shared\Infrastructure\List\CatalogsList;
use App\Shared\Infrastructure\List\TasksGroupsList;
use App\Shared\Infrastructure\List\TasksList;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setHref(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->background_color = $backgroundColor;

        return $this;
    }

    public function setTextColor(string $textColor): self
    {
        $this->text_color = $textColor;

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new BookmarkId($this->getId()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->getHref(),
            $this->getIcon(),
            $this->isAssignedToDashboard(),
            new Hex($this->getBackgroundColor()),
            new Hex($this->getTextColor()),
            new CatalogsList($this->catalogs()),
            new TasksList($this->tasks()),
            new TasksGroupsList($this->tasksGroups())
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assigned_to_dashboard;
    }

    public function getBackgroundColor(): string
    {
        return $this->background_color;
    }

    public function getTextColor(): string
    {
        return $this->text_color;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_bookmarks',
            'bookmark_id',
            'catalog_id'
        );
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'tasks_bookmarks',
            'bookmark_id',
            'task_id'
        );
    }

    public function tasksGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskGroup::class,
            'tasks_groups_bookmarks',
            'bookmark_id',
            'task_id'
        );
    }

    public function setAssignedToDashboard(bool $assignedToDashboard): self
    {
        $this->assigned_to_dashboard = $assignedToDashboard;

        return $this;
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->isAssignedToDashboard(),
            $this->getHref(),
            $this->getIcon(),
            $this->getBackgroundColor(),
            $this->getTextColor(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new TasksGroupsIdsList(
                ...$this->tasksGroups->pluck('id')->map(fn(string $id) => new TasksGroupId($id))->toArray()
            )
        );
    }
}
