<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Application\ViewModel\NotificationsList;
use App\Alarm\Application\ViewModel\SingleAlarm as ViewModel;
use App\Alarm\Domain\Entity\SingleAlarm as DomainModel;
use App\Alarm\Infrastructure\Dto\NotificationList;
use App\Catalog\Infrastructure\Model\Catalog;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Infrastructure\List\CatalogsList;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alarm extends Model
{
    protected $table = 'alarms';

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

    public function setTaskId(?TaskId $taskId): self
    {
        $this->task_id = $taskId?->getValue();

        return $this;
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

    public function setDate(?DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function setUserId(UserId $userId): self
    {
        $this->user_id = $userId->getValue();

        return $this;
    }

    public function setDeactivationCode(string $deactivationCode): self
    {
        $this->deactivation_code = $deactivationCode;

        return $this;
    }

    public function setAlarmsGroupId(?AlarmsGroupId $alarmsGroupId): self
    {
        $this->group_id = $alarmsGroupId?->getValue();

        return $this;
    }

    public function toDomainModel(): DomainModel
    {
        return new DomainModel(
            new AlarmId($this->getId()),
            $this->getGroupId() === null ? null : new AlarmsGroupId($this->getGroupId()),
            $this->getTaskId() === null ? null : new TaskId($this->getTaskId()),
            $this->isChecked(),
            new NotificationList($this->notifications()),
            $this->getDate()?->toDateTimeImmutable(),
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

    public function getGroupId(): ?string
    {
        return $this->group_id;
    }

    public function getTaskId(): ?string
    {
        return $this->task_id;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'alarm_id', 'id');
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
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
            'catalogs_alarms',
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
            $this->isChecked(),
            $this->getGroupId(),
            $this->getTaskId(),
            new CatalogsIdsList(...$this->catalogs->pluck('id')->map(fn(int $id) => new CatalogId($id))->toArray()),
            new NotificationsList(
                ...$this->notifications->map(fn(Notification $notification) => $notification->toViewModel())->toArray()
            ),
            $this->getDate()?->toDateTimeImmutable(),
            $this->getCreatedAt()->toDateTimeImmutable()
        );
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function setId(AlarmId $id): self
    {
        $this->id = $id->getValue();

        return $this;
    }
}
