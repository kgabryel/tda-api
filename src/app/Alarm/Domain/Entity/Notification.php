<?php

namespace App\Alarm\Domain\Entity;

use App\Shared\Domain\Entity\AlarmId;
use DateTime;
use DateTimeImmutable;

class Notification
{
    private NotificationId $notificationId;
    private AlarmId $alarmId;
    private DateTime $time;
    private bool $checked;
    private NotificationTypesList $typesList;

    public function __construct(
        NotificationId $notificationId,
        AlarmId $alarmId,
        DateTime $time,
        bool $checked,
        NotificationTypesList $typesList
    ) {
        $this->notificationId = $notificationId;
        $this->alarmId = $alarmId;
        $this->time = $time;
        $this->checked = $checked;
        $this->typesList = $typesList;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getTime(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($this->time);
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function check(): void
    {
        $this->checked = true;
    }

    public function uncheck(): bool
    {
        if (!$this->checked) {
            return false;
        }
        if ($this->time->getTimestamp() <= time()) {
            return false;
        }
        $this->checked = false;

        return true;
    }

    public function getTypesList(): NotificationTypesList
    {
        return $this->typesList;
    }
}
