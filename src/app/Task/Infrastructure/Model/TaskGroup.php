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
use App\Shared\Domain\Entity\AlarmsGroupId;
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
use App\Task\Application\ViewModel\PeriodicTask as ViewModel;
use App\Task\Domain\Entity\PeriodicTask as DomainModel;
use App\Task\Infrastructure\Dto\TasksList;
use App\Video\Infrastructure\Model\Video;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskGroup extends Model
{
    protected $table = 'tasks_groups';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start' => 'date',
        'stop' => 'date'
    ];

    public function getIncrementing(): bool
    {
        return false;
    }

    public function setId(TasksGroupId $id): self
    {
        $this->id = $id->getValue();

        return $this;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setStart(DateTimeImmutable $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function setStop(?DateTimeImmutable $stop): self
    {
        $this->stop = $stop;

        return $this;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function setIntervalType(string $intervalType): self
    {
        $this->interval_type = $intervalType;

        return $this;
    }

    public function setActiveValue(bool $isActive): self
    {
        $this->active = $isActive;

        return $this;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->getContent(),
            $this->getInterval(),
            $this->getIntervalType(),
            $this->getStart()->toDateTimeImmutable(),
            $this->getStop()?->toDateTimeImmutable(),
            $this->getAlarmId(),
            $this->isActive(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new NotesIdsList(...$this->notes->pluck('id')->map(fn(int $id) => new NoteId($id))->toArray()),
            new BookmarksIdsList(...$this->bookmarks->pluck('id')->map(fn(int $id) => new BookmarkId($id))->toArray()),
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

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getIntervalType(): string
    {
        return $this->interval_type;
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function getStop(): ?Carbon
    {
        return $this->stop;
    }

    public function getAlarmId(): ?string
    {
        return $this->alarmId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new TasksGroupId($this->getId()),
            $this->getAlarmId() === null ? null : new AlarmsGroupId($this->getAlarmId()),
            new TasksList($this->tasks()),
            $this->isActive(),
            $this->getStart()->toDateTimeImmutable(),
            $this->getStop()?->toDateTimeImmutable(),
            $this->getInterval(),
            $this->getIntervalType(),
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

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'group_id', 'id')
            ->select('tasks.*', 'tasks_statuses.name as statusName', 'alarms.id as alarmId')
            ->join('tasks_statuses', 'tasks_statuses.id', '=', 'tasks.status_id')
            ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_tasks_groups',
            'task_id',
            'catalog_id'
        );
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(
            Note::class,
            'tasks_groups_notes',
            'task_id',
            'note_id'
        );
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Bookmark::class,
            'tasks_groups_bookmarks',
            'task_id',
            'bookmark_id'
        );
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'tasks_groups_files',
            'task_id',
            'file_id'
        );
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(
            Video::class,
            'tasks_groups_videos',
            'task_id',
            'video_id'
        );
    }
}
