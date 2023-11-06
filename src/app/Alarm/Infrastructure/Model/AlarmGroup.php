<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Application\ViewModel\PeriodicAlarm as ViewModel;
use App\Alarm\Domain\Entity\PeriodicAlarm as DomainModel;
use App\Alarm\Infrastructure\Dto\AlarmsList;
use App\Alarm\Infrastructure\Dto\NotificationsGroupList;
use App\Catalog\Infrastructure\Model\Catalog;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Infrastructure\List\CatalogsList;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmGroup extends Model
{
    protected $table = 'alarms_groups';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start' => 'date',
        'stop' => 'date'
    ];

    public function setId(AlarmsGroupId $id): self
    {
        $this->id = $id->getValue();

        return $this;
    }

    public function setTaskGroup(?TasksGroupId $id): self
    {
        $this->task_id = $id?->getValue();

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

    public function getIncrementing(): bool
    {
        return false;
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

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new AlarmsGroupId($this->getId()),
            new AlarmsList($this->alarms()),
            $this->getTaskId() === null ? null : new TasksGroupId($this->getTaskId()),
            $this->isActive(),
            $this->getStart()->toDateTimeImmutable(),
            $this->getStop()?->toDateTimeImmutable(),
            $this->getInterval(),
            $this->getIntervalType(),
            new NotificationsGroupList($this->notificationsGroups()),
            new UserId($this->getUserId()),
            $this->getName(),
            $this->getContent(),
            new CatalogsList($this->catalogs())
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class, 'group_id', 'id');
    }

    public function getTaskId(): ?string
    {
        return $this->task_id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function getStop(): ?Carbon
    {
        return $this->stop;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getIntervalType(): string
    {
        return $this->interval_type;
    }

    public function notificationsGroups(): HasMany
    {
        return $this->hasMany(NotificationGroup::class, 'alarm_id', 'id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany(
            Catalog::class,
            'catalogs_alarms_groups',
            'alarm_id',
            'catalog_id'
        );
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
            $this->getTaskId(),
            new SingleAlarmsIdsList(...$this->alarms->pluck('id')->map(fn(string $id) => new AlarmId($id))->toArray()),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            $this->isActive(),
            $this->getCreatedAt()->toDateTimeImmutable(),
            ...
            $this->notificationsGroups->map(
                static fn(NotificationGroup $notificationGroup) => $notificationGroup->toViewModel()
            )->toArray()
        );
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }
}
