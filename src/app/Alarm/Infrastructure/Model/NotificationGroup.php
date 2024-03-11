<?php

namespace App\Alarm\Infrastructure\Model;

use App\Alarm\Application\ViewModel\NotificationGroup as ViewModel;
use App\Alarm\Application\ViewModel\NotificationsTypesList as ViewNotificationsTypesList;
use App\Alarm\Domain\Entity\NotificationGroup as DomainModel;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\NotificationTypeId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Domain\Entity\AlarmsGroupId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationGroup extends Model
{
    protected $table = 'notifications_groups';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function setAlarmId(AlarmsGroupId $alarmId): self
    {
        $this->alarm_id = $alarmId->getValue();

        return $this;
    }

    public function getAlarmId(): string
    {
        return $this->alarm_id;
    }

    public function toDomainModel(): DomainModel
    {
        $notificationTypes = $this->notificationTypes?->pluck('id')
            ->map(fn(int $id) => new NotificationTypeId($id))->toArray() ?? [];

        return new DomainModel(
            new NotificationsGroupId($this->getId()),
            $this->getTime(),
            new NotificationTypesList(...$notificationTypes)
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function notificationTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationsType::class,
            'notifications_groups_types',
            'group_id',
            'type_id'
        );
    }

    public function setHour(string $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function setIntervalBehaviour(string $intervalBehaviour): self
    {
        $this->interval_behaviour = $intervalBehaviour;

        return $this;
    }

    public function setInterval(?int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function toViewModel(): ViewModel
    {
        return new ViewModel(
            $this->getId(),
            $this->getHour(),
            $this->getIntervalBehaviour(),
            $this->getInterval(),
            new ViewNotificationsTypesList(...($this->notificationTypes?->pluck('id')->toArray() ?? []))
        );
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getIntervalBehaviour(): string
    {
        return $this->interval_behaviour;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }
}
