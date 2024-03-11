<?php

namespace App\Alarm\Domain\Event\Port;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\Event;
use App\Shared\Application\Dto\DatesList;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * zostaly dodane nowe alarmy pojedyncze dla alarmu okresowego, nalezy go utworzyc
 */
interface AlarmsAdded extends Event
{
    public function getAlarmGroupId(): AlarmsGroupId;

    public function getTaskGroup(): TasksGroupsList;

    public function getDates(): DatesList;
}
