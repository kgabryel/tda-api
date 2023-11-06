<?php

namespace App\Video\Infrastructure\Model;

use App\Catalog\Infrastructure\Model\Catalog;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\List\CatalogsList;
use App\Shared\Infrastructure\List\TasksGroupsList;
use App\Shared\Infrastructure\List\TasksList;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use App\Video\Application\ViewModel\Video as ViewModel;
use App\Video\Domain\Entity\Video as DomainModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    protected $table = 'videos';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setWatched(bool $watched): self
    {
        $this->watched = $watched;

        return $this;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setAssignedToDashboard(bool $assignedToDashboard): self
    {
        $this->assigned_to_dashboard = $assignedToDashboard;

        return $this;
    }

    public function setYtId(string $ytId): self
    {
        $this->yt_id = $ytId;

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new VideoId($this->getId()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->isAssignedToDashboard(),
            $this->isWatched(),
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

    public function isAssignedToDashboard(): bool
    {
        return $this->assigned_to_dashboard;
    }

    public function isWatched(): bool
    {
        return $this->watched;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_videos',
            'video_id',
            'catalog_id'
        );
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'tasks_videos',
            'video_id',
            'task_id'
        );
    }

    public function tasksGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskGroup::class,
            'tasks_groups_videos',
            'video_id',
            'task_id'
        );
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getYtId(),
            $this->isWatched(),
            $this->isAssignedToDashboard(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new TasksGroupsIdsList(
                ...$this->tasksGroups->pluck('id')->map(fn(string $id) => new TasksGroupId($id))->toArray()
            )
        );
    }

    public function getYtId(): string
    {
        return $this->yt_id;
    }
}
