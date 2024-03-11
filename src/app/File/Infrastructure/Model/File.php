<?php

namespace App\File\Infrastructure\Model;

use App\Catalog\Infrastructure\Model\Catalog;
use App\File\Application\ViewModel\File as ViewModel;
use App\File\Domain\Entity\File as DomainModel;
use App\File\Infrastructure\FileData;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Infrastructure\List\CatalogsList;
use App\Shared\Infrastructure\List\TasksGroupsList;
use App\Shared\Infrastructure\List\TasksList;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends Model
{
    protected $table = 'files';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->original_name = $originalName;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function setParsedSize(string $parsedSize): self
    {
        $this->parsed_size = $parsedSize;

        return $this;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mime_type = $mimeType;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setAssignedToDashboard(bool $assignedToDashboard): self
    {
        $this->assigned_to_dashboard = $assignedToDashboard;

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new FileId($this->getId()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->isAssignedToDashboard(),
            new FileData(
                $this->getSize(),
                $this->getMimeType(),
                $this->getExtension(),
                $this->getOriginalName(),
                $this->getPath()
            ),
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

    public function getSize(): int
    {
        return $this->size;
    }

    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getOriginalName(): string
    {
        return $this->original_name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_files',
            'file_id',
            'catalog_id'
        );
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'tasks_files',
            'file_id',
            'task_id'
        );
    }

    public function tasksGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskGroup::class,
            'tasks_groups_files',
            'file_id',
            'task_id'
        );
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getOriginalName(),
            $this->getParsedSize(),
            $this->getExtension(),
            $this->getCreatedAt()->toDateTimeImmutable(),
            $this->getUpdatedAt()->toDateTime(),
            $this->isAssignedToDashboard(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new TasksGroupsIdsList(
                ...$this->tasksGroups->pluck('id')->map(fn(string $id) => new TasksGroupId($id))->toArray()
            )
        );
    }

    public function getParsedSize(): string
    {
        return $this->parsed_size;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }
}
