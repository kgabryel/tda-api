<?php

namespace App\Alarm\Application\Command\SingleAlarm\Create;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TaskId;
use DateTimeImmutable;

class AlarmDto
{
    private AlarmId $alarmId;
    private string $name;
    private ?string $content;
    private CatalogsIdsList $catalogsList;
    private ?DateTimeImmutable $date;
    private ?AlarmsGroupId $alarmsGroupId;
    private ?TaskId $taskId;

    public function __construct(AlarmId $alarmId, string $name, ?string $content, CatalogsIdsList $catalogsList)
    {
        $this->alarmId = $alarmId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
        $this->date = null;
        $this->alarmsGroupId = null;
        $this->taskId = null;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getAlarmsGroupId(): ?AlarmsGroupId
    {
        return $this->alarmsGroupId;
    }

    public function setAlarmsGroupId(AlarmsGroupId $alarmsGroupId): void
    {
        $this->alarmsGroupId = $alarmsGroupId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
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

    public function getTaskId(): ?TaskId
    {
        return $this->taskId;
    }

    public function setTaskId(?TaskId $taskId): void
    {
        $this->taskId = $taskId;
    }
}
