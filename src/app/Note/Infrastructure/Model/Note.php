<?php

namespace App\Note\Infrastructure\Model;

use App\Catalog\Infrastructure\Model\Catalog;
use App\Note\Application\ViewModel\Note as ViewModel;
use App\Note\Domain\Entity\Note as DomainModel;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;
use App\Shared\Infrastructure\List\CatalogsList;
use App\Shared\Infrastructure\List\TasksGroupsList;
use App\Shared\Infrastructure\List\TasksList;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model
{
    protected $table = 'notes';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->background_color = $backgroundColor;

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new NoteId($this->getId()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->getContent(),
            new Hex($this->getBackgroundColor()),
            new Hex($this->getTextColor()),
            $this->isAssignedToDashboard(),
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function getBackgroundColor(): string
    {
        return $this->background_color;
    }

    public function getTextColor(): string
    {
        return $this->text_color;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assigned_to_dashboard;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_notes',
            'note_id',
            'catalog_id'
        );
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'tasks_notes',
            'note_id',
            'task_id'
        );
    }

    public function tasksGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskGroup::class,
            'tasks_groups_notes',
            'note_id',
            'task_id'
        );
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getContent(),
            $this->getTextColor(),
            $this->getBackgroundColor(),
            $this->getCreatedAt()->toDateTimeImmutable(),
            $this->isAssignedToDashboard(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new TasksGroupsIdsList(
                ...$this->tasksGroups->pluck('id')->map(fn(string $id) => new TasksGroupId($id))->toArray()
            )
        );
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function setTextColor(string $textColor): self
    {
        $this->text_color = $textColor;

        return $this;
    }

    public function setAssignedToDashboard(bool $assignedToDashboard): self
    {
        $this->assigned_to_dashboard = $assignedToDashboard;

        return $this;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }
}
