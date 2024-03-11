<?php

namespace App\Task\Infrastructure\Model;

use App\Bookmark\Infrastructure\Model\Bookmark;
use App\Catalog\Infrastructure\Model\Catalog;
use App\File\Infrastructure\Model\File;
use App\Note\Infrastructure\Model\Note;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\List\BookmarksList;
use App\Shared\Infrastructure\List\CatalogsList;
use App\Shared\Infrastructure\List\FilesList;
use App\Shared\Infrastructure\List\NotesList;
use App\Shared\Infrastructure\List\VideosList;
use App\Task\Application\ViewModel\SingleTask as ViewModel;
use App\Task\Domain\Entity\SingleTask as DomainModel;
use App\Task\Domain\Entity\StatusId;
use App\Task\Domain\Entity\TaskStatus as TaskStatusEntity;
use App\Task\Domain\TaskStatus;
use App\Task\Infrastructure\Dto\SubtasksList;
use App\Video\Infrastructure\Model\Video;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $table = 'tasks';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date' => 'date'
    ];

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function setId(TaskId $id): self
    {
        $this->id = $id->getValue();

        return $this;
    }

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

    public function setMainTaskId(?TaskId $id): self
    {
        $this->parent_id = $id?->getValue();

        return $this;
    }

    public function setGroupId(?TasksGroupId $id): self
    {
        $this->group_id = $id?->getValue();

        return $this;
    }

    public function setStatusId(StatusId $statusId): self
    {
        $this->status_id = $statusId->getValue();

        return $this;
    }

    public function setStatusName(string $statusName): self
    {
        $this->statusName = $statusName;

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setMainTask(?TaskId $id): self
    {
        $this->parent_id = $id?->getValue();

        return $this;
    }

    public function setDate(?DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function changeStatusId(StatusId $statusId): self
    {
        $this->status_id = $statusId->getValue();

        return $this;
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getContent(),
            $this->getDate()?->toDateTimeImmutable(),
            $this->getStatusId(),
            $this->getParentId(),
            $this->getGroupId(),
            $this->getAlarmId(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->subtasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new NotesIdsList(...$this->notes->pluck('id')->map(fn(int $id) => new NoteId($id))->toArray()),
            new BookmarksIdsList(
                ...$this->bookmarks->pluck('id')->map(fn(int $id) => new BookmarkId($id))->toArray()
            ),
            new FilesIdsList(...$this->files->pluck('id')->map(fn(int $id) => new FileId($id))->toArray()),
            new VideosIdsList(...$this->videos->pluck('id')->map(fn(int $id) => new VideoId($id))->toArray()),
            $this->getCreatedAt()->toDateTimeImmutable()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function getParentId(): ?string
    {
        return $this->parent_id;
    }

    public function getGroupId(): ?string
    {
        return $this->group_id;
    }

    public function getAlarmId(): ?string
    {
        return $this->alarmId;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new TaskId($this->getId()),
            $this->getGroupId() === null ? null : new TasksGroupId($this->getGroupId()),
            $this->getParentId() === null ? null : new TaskId($this->getParentId()),
            new SubtasksList($this->subtasks()),
            $this->getAlarmId() === null ? null : new AlarmId($this->getAlarmId()),
            new TaskStatusEntity(
                new StatusId($this->getStatusId()),
                TaskStatus::from($this->getStatus())
            ),
            $this->getDate()?->toDateTimeImmutable(),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->getContent(),
            new CatalogsList($this->catalogs()),
            new NotesList($this->notes()),
            new BookmarksList($this->bookmarks()),
            new FilesList($this->files()),
            new VideosList($this->videos())
        );
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->select('tasks.*', 'tasks_statuses.name as statusName', 'alarms.id as alarmId')
            ->join('tasks_statuses', 'tasks_statuses.id', '=', 'tasks.status_id')
            ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id');
    }

    public function getStatus(): string
    {
        return $this->statusName;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_tasks',
            'task_id',
            'catalog_id'
        );
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(
            Note::class,
            'tasks_notes',
            'task_id',
            'note_id'
        );
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Bookmark::class,
            'tasks_bookmarks',
            'task_id',
            'bookmark_id'
        );
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'tasks_files',
            'task_id',
            'file_id'
        );
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(
            Video::class,
            'tasks_videos',
            'task_id',
            'video_id'
        );
    }
}
