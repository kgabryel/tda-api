<?php

namespace App\Alarm\Application\Service;

use App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm\CreateAlarmsForPeriodicAlarm;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TaskId;
use DateTimeImmutable;

class CreateData
{
    private AlarmsGroupId $alarmsGroupId;
    private string $name;
    private ?string $content;
    private DateTimeImmutable $date;
    private array $groups;
    private ?AlarmId $alarmId;
    private ?TaskId $taskId;
    private bool $fromGroup;

    public function __construct(
        AlarmsGroupId $alarmsGroupId,
        string $name,
        ?string $content,
        DateTimeImmutable $date,
        array $groups,
        ?AlarmId $alarmId = null,
        ?TaskId $taskId = null,
        bool $fromGroup = false
    ) {
        $this->alarmsGroupId = $alarmsGroupId;
        $this->name = $name;
        $this->content = $content;
        $this->date = $date;
        $this->groups = $groups;
        $this->alarmId = $alarmId;
        $this->taskId = $taskId;
        $this->fromGroup = $fromGroup;
    }

    public static function createFromPeriodicAlarm(
        PeriodicAlarm $periodicAlarm,
        DateTimeImmutable $date,
        ?AlarmId $alarmId = null,
        ?TaskId $taskId = null,
        bool $fromGroup = false
    ): self {
        return new self(
            $periodicAlarm->getAlarmId(),
            $periodicAlarm->getName(),
            $periodicAlarm->getContent(),
            $date,
            $periodicAlarm->getNotificationsGroups(),
            $alarmId,
            $taskId,
            $fromGroup
        );
    }

    public function getAlarmId(): ?AlarmId
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

    public static function createFromCommand(
        CreateAlarmsForPeriodicAlarm $command,
        DateTimeImmutable $date,
        AlarmId $alarmId,
        ?TaskId $taskId
    ): self {
        return new self(
            $command->getAlarmId(),
            $command->getName(),
            $command->getContent(),
            $date,
            $command->getGroups()->get(),
            $alarmId,
            $taskId,
            true
        );
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getAlarmsGroupId(): AlarmsGroupId
    {
        return $this->alarmsGroupId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getTaskId(): ?TaskId
    {
        return $this->taskId;
    }

    public function isFromGroup(): bool
    {
        return $this->fromGroup;
    }
}
