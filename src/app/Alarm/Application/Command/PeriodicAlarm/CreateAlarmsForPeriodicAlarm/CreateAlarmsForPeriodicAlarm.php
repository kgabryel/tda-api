<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Alarm\Application\Dto\NotificationsGroupsList;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\DatesList;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

/**
 * Tworzy alarmy pojedyncze dla nowo utworzonego alarmu okresowego
 */
#[CommandHandler(CreateAlarmsForPeriodicAlarmHandler::class)]
class CreateAlarmsForPeriodicAlarm implements Command
{
    private DatesList $dates;
    private ?TasksGroupsList $taskGroup;
    private AlarmsGroupId $alarmId;
    private NotificationsGroupsList $groups;
    private UserId $userId;
    private string $name;
    private ?string $content;

    public function __construct(
        DatesList $dates,
        ?TasksGroupsList $taskGroup,
        AlarmsGroupId $alarmId,
        NotificationsGroupsList $groups,
        UserId $userId,
        string $name,
        ?string $content
    ) {
        $this->dates = $dates;
        $this->taskGroup = $taskGroup;
        $this->alarmId = $alarmId;
        $this->groups = $groups;
        $this->userId = $userId;
        $this->name = $name;
        $this->content = $content;
    }

    public static function fromPeriodicAlarm(
        DatesList $dates,
        PeriodicAlarm $alarm,
        ?TasksGroupsList $taskGroup,
        NotificationsGroupsList $groups
    ): self {
        return new self(
            $dates,
            $taskGroup,
            $alarm->getAlarmId(),
            $groups,
            $alarm->getUserId(),
            $alarm->getName(),
            $alarm->getContent(),
        );
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getDates(): DatesList
    {
        return $this->dates;
    }

    public function getGroups(): NotificationsGroupsList
    {
        return $this->groups;
    }

    public function getTaskGroup(): ?TasksGroupsList
    {
        return $this->taskGroup;
    }
}
