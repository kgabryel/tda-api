<?php

namespace App\Catalog\Infrastructure\Model;

use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\AlarmGroup;
use App\Bookmark\Infrastructure\Model\Bookmark;
use App\Catalog\Application\ViewModel\Catalog as ViewModel;
use App\Catalog\Domain\Entity\Catalog as DomainModel;
use App\File\Infrastructure\Model\File;
use App\Note\Infrastructure\Model\Note;
use App\Shared\Application\Dto\AlarmsGroupsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\List\AlarmsGroupsList;
use App\Shared\Infrastructure\List\AlarmsList;
use App\Shared\Infrastructure\List\BookmarksList;
use App\Shared\Infrastructure\List\FilesList;
use App\Shared\Infrastructure\List\NotesList;
use App\Shared\Infrastructure\List\TasksGroupsList;
use App\Shared\Infrastructure\List\TasksList;
use App\Shared\Infrastructure\List\VideosList;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use App\Video\Infrastructure\Model\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Catalog extends Model
{
    protected $table = 'catalogs';

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

    public function setAssignedToDashboard(bool $assignedToDashboard): self
    {
        $this->assigned_to_dashboard = $assignedToDashboard;

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new CatalogId($this->getId()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->isAssignedToDashboard(),
            new TasksList($this->tasks()),
            new TasksGroupsList($this->tasksGroups()),
            new NotesList($this->notes()),
            new BookmarksList($this->bookmarks()),
            new FilesList($this->files()),
            new VideosList($this->videos()),
            new AlarmsList($this->alarms()),
            new AlarmsGroupsList($this->alarmsGroups())
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

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'catalogs_tasks',
            'catalog_id',
            'task_id'
        );
    }

    public function tasksGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskGroup::class,
            'catalogs_tasks_groups',
            'catalog_id',
            'task_id'
        );
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(
            Note::class,
            'catalogs_notes',
            'catalog_id',
            'note_id'
        );
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Bookmark::class,
            'catalogs_bookmarks',
            'catalog_id',
            'bookmark_id'
        );
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'catalogs_files',
            'catalog_id',
            'file_id'
        );
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(
            Video::class,
            'catalogs_videos',
            'catalog_id',
            'video_id'
        );
    }

    public function alarms(): BelongsToMany
    {
        return $this->belongsToMany(
            Alarm::class,
            'catalogs_alarms',
            'catalog_id',
            'alarm_id'
        );
    }

    public function alarmsGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            AlarmGroup::class,
            'catalogs_alarms_groups',
            'catalog_id',
            'alarm_id'
        );
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getName(),
            $this->isAssignedToDashboard(),
            new SingleAlarmsIdsList(...$this->alarms->pluck('id')->map(fn(string $id) => new AlarmId($id))->toArray()),
            new AlarmsGroupsIdsList(
                ...$this->alarmsGroups->pluck('id')->map(fn(string $id) => new AlarmsGroupId($id))->toArray()
            ),
            new SingleTasksIdsList(...$this->tasks->pluck('id')->map(fn(string $id) => new TaskId($id))->toArray()),
            new TasksGroupsIdsList(
                ...$this->tasksGroups->pluck('id')->map(fn(string $id) => new TasksGroupId($id))->toArray()
            ),
            new NotesIdsList(...$this->notes->pluck('id')->map(fn(int $id) => new NoteId($id))->toArray()),
            new BookmarksIdsList(
                ...$this->bookmarks->pluck('id')->map(fn(int $id) => new BookmarkId($id))->toArray()
            ),
            new FilesIdsList(...$this->files->pluck('id')->map(fn(int $id) => new FileId($id))->toArray()),
            new VideosIdsList(...$this->videos->pluck('id')->map(fn(int $id) => new VideoId($id))->toArray())
        );
    }
}
