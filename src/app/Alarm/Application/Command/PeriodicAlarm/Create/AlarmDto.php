<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\IntervalType;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TasksGroupId;
use DateTimeImmutable;

class AlarmDto
{
    private AlarmsGroupId $alarmId;
    private string $name;
    private ?string $content;
    private CatalogsIdsList $catalogsList;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private int $interval;
    private IntervalType $intervalType;
    private ?TasksGroupId $tasksGroupId;

    public function __construct(
        AlarmsGroupId $alarmId,
        string $name,
        ?string $content,
        CatalogsIdsList $catalogsList,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        int $interval,
        IntervalType $intervalType
    ) {
        $this->alarmId = $alarmId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
        $this->start = $start;
        $this->stop = $stop;
        $this->interval = $interval;
        $this->intervalType = $intervalType;
        $this->tasksGroupId = null;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function getTasksGroupId(): ?TasksGroupId
    {
        return $this->tasksGroupId;
    }

    public function setTasksGroupId(TasksGroupId $tasksGroupId): void
    {
        $this->tasksGroupId = $tasksGroupId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): ?DateTimeImmutable
    {
        return $this->stop;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getIntervalType(): IntervalType
    {
        return $this->intervalType;
    }
}
