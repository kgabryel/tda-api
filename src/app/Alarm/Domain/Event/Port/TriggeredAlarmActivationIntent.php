<?php

namespace App\Alarm\Domain\Event\Port;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * wywolano intencje aktywowania alarmu okresowego - nalezy wykonac odpowiednia opercje
 */
interface TriggeredAlarmActivationIntent extends Event
{
    public function getAlarmId(): AlarmsGroupId;

    public function getAction(): string;

    public function getTaskGroup(): ?TasksGroupsList;
}
