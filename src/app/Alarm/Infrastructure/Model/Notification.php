<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Application\ViewModel\Notification as ViewModel;
use App\Alarm\Application\ViewModel\NotificationsTypesList as ViewNotificationsTypesList;
use App\Alarm\Domain\Entity\Notification as DomainModel;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\NotificationTime;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Domain\Entity\AlarmId;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'time' => 'datetime'
    ];

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function setAlarmId(AlarmId $alarmId): self
    {
        $this->alarm_id = $alarmId->getValue();

        return $this;
    }

    public function setNotificationsGroupId(NotificationsGroupId $notificationsGroupId): self
    {
        $this->group_id = $notificationsGroupId->getValue();

        return $this;
    }

    public function getTypesNames(): array
    {
        return $this->getTypes()->pluck('name')->toArray();
    }

    public function getTypes(): Collection
    {
        return $this->notificationTypes()->get();
    }

    public function notificationTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationsType::class,
            'notifications_types',
            'notification_id',
            'type_id'
        );
    }

    public function toDomainModel(): DomainModel
    {
        $notificationTypes = $this->notificationTypes?->pluck('id')
            ->map(fn(int $id) => new NotificationTypeId($id))->toArray() ?? [];

        return new DomainModel(
            new NotificationId($this->getId()),
            new AlarmId($this->getAlarmId()),
            $this->getTime()->toDateTime(),
            $this->isChecked(),
            new NotificationTypesList(...$notificationTypes)
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlarmId(): string
    {
        return $this->alarm_id;
    }

    public function getTime(): Carbon
    {
        return $this->time;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function toNotificationTime(): NotificationTime
    {
        return new NotificationTime(
            new NotificationId($this->getId()),
            new AlarmId($this->getAlarmId()),
            $this->getTime()->toDateTimeImmutable(),
        );
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getTime()->toDateTimeImmutable(),
            $this->isChecked(),
            new ViewNotificationsTypesList(...($this->notificationTypes?->pluck('id')->toArray() ?? []))
        );
    }

    public function setTime(DateTimeImmutable $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function alarm(): BelongsTo
    {
        return $this->belongsTo(Alarm::class, 'alarm_id', 'id');
    }
}
