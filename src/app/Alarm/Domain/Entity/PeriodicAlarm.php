<?php

namespace App\Alarm\Domain\Entity;

use App\Shared\Application\IntervalType;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use DateTimeImmutable;

class PeriodicAlarm extends Alarm
{
    private AlarmsGroupId $alarmId;
    private AlarmsList $alarms;
    private ?TasksGroupId $taskId;
    private bool $active;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private int $interval;
    private IntervalType $intervalType;
    private NotificationsGroupsList $notificationsGroupsList;

    public function __construct(
        AlarmsGroupId $alarmId,
        AlarmsList $alarms,
        ?TasksGroupId $taskId,
        bool $active,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        int $interval,
        string $intervalType,
        NotificationsGroupsList $notificationsGroupsList,
        UserId $userId,
        string $name,
        ?string $content,
        CatalogsListInterface $catalogsList
    ) {
        parent::__construct($userId, $name, $content, $catalogsList);
        $this->alarmId = $alarmId;
        $this->alarms = $alarms;
        $this->taskId = $taskId;
        $this->active = $active;
        $this->start = $start;
        $this->stop = $stop;
        $this->interval = $interval;
        $this->intervalType = IntervalType::from($intervalType);
        $this->notificationsGroupsList = $notificationsGroupsList;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function updateName(string $name): bool
    {
        $result = parent::updateName($name);
        if (!$result) {
            return false;
        }
        $this->alarms->updateName($name);

        return true;
    }

    public function updateContent(?string $content): bool
    {
        $result = parent::updateContent($content);
        if (!$result) {
            return false;
        }
        $this->alarms->updateContent($content);

        return true;
    }

    public function disconnectAlarms(): void
    {
        $this->alarms->disconnect();
    }

    public function hasTask(): bool
    {
        return $this->taskId !== null;
    }

    public function getTaskId(): ?TasksGroupId
    {
        return $this->taskId;
    }

    public function deactivate(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "active" value, entity was deleted.');
        }
        if (!$this->active) {
            return false;
        }
        $this->active = false;

        return true;
    }

    public function activate(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "active" value, entity was deleted.');
        }
        if ($this->active) {
            return false;
        }
        $this->active = true;

        return true;
    }

    public function getAlarmsInFuture(): AlarmsInFuture
    {
        return $this->alarms->getAlarmsInFuture();
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): ?DateTimeImmutable
    {
        return $this->stop;
    }

    public function getIntervalType(): IntervalType
    {
        return $this->intervalType;
    }

    public function getNotificationsGroups(): array
    {
        return $this->notificationsGroupsList->get();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getAlarmsIds(): array
    {
        return $this->alarms->getIds();
    }

    public function getConnectedTasksIds(): array
    {
        return $this->alarms->getConnectedTasksIds();
    }
}
