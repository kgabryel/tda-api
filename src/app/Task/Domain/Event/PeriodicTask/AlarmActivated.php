<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Alarm\Domain\Event\Port\TriggeredAlarmActivationIntent;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Podany alarm okresowy zostal aktywowany podczas aktywacji zadania okresowego
 */
class AlarmActivated implements TriggeredAlarmActivationIntent
{
    private AlarmsGroupId $id;
    private string $action;

    private ?TasksGroupsList $taskGroup;

    public function __construct(AlarmsGroupId $id, string $action, ?TasksGroupsList $taskGroup = null)
    {
        $this->id = $id;
        $this->action = $action;
        $this->taskGroup = $taskGroup;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->id;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getTaskGroup(): ?TasksGroupsList
    {
        return $this->taskGroup;
    }
}
