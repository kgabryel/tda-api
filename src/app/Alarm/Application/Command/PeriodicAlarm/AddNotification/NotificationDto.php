<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\AddNotification;

use App\Alarm\Domain\Entity\NotificationTypesList;

class NotificationDto
{
    private int $time;
    private NotificationTypesList $typesList;
    private string $hour;
    private string $intervalBehaviour;
    private ?int $interval;

    public function __construct(
        int $time,
        NotificationTypesList $typesList,
        string $hour,
        string $intervalBehaviour,
        ?int $interval
    ) {
        $this->time = $time;
        $this->typesList = $typesList;
        $this->hour = $hour;
        $this->intervalBehaviour = $intervalBehaviour;
        $this->interval = $interval;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getTypesList(): NotificationTypesList
    {
        return $this->typesList;
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getIntervalBehaviour(): string
    {
        return $this->intervalBehaviour;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }
}
