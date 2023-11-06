<?php

namespace App\Alarm\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use DateTimeImmutable;
use JsonSerializable;

class PeriodicAlarm implements JsonSerializable
{
    private string $id;
    private string $name;
    private ?string $content;
    private int $interval;
    private string $intervalType;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private ?string $connectedTask;
    private SingleAlarmsIdsList $alarmsList;
    private CatalogsIdsList $catalogsList;
    private bool $isActive;
    private DateTimeImmutable $createdAt;
    private array $notifications;

    public function __construct(
        string $id,
        string $name,
        ?string $content,
        int $interval,
        string $intervalType,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        ?string $connectedTask,
        SingleAlarmsIdsList $alarmsList,
        CatalogsIdsList $catalogsList,
        bool $isActive,
        DateTimeImmutable $createdAt,
        NotificationGroup ...$notifications
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->interval = $interval;
        $this->intervalType = $intervalType;
        $this->start = $start;
        $this->stop = $stop;
        $this->connectedTask = $connectedTask;
        $this->alarmsList = $alarmsList;
        $this->catalogsList = $catalogsList;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->notifications = $notifications;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'interval' => $this->interval,
            'intervalType' => $this->intervalType,
            'start' => $this->start->format('Y-m-d'),
            'stop' => $this->stop?->format('Y-m-d'),
            'task' => $this->connectedTask,
            'alarms' => $this->alarmsList->getIds(),
            'periodic' => true,
            'catalogs' => $this->catalogsList->getIds(),
            'active' => $this->isActive,
            'order' => $this->createdAt->getTimestamp(),
            'notificationsGroups' => $this->notifications
        ];
    }
}
