<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Domain\Event\Port\TriggeredAlarmDeactivationIntent;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Podany alarm okresowy zostal dezaktywowany podczas dezaktywowania zadania okresowego
 */
class AlarmDeactivated implements TriggeredAlarmDeactivationIntent
{
    private AlarmsGroupId $id;
    private string $action;

    public function __construct(AlarmsGroupId $id, string $action)
    {
        $this->id = $id;
        $this->action = $action;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->id;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
