<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Alarm\Domain\Event\Port\AlarmsAdded as AlarmsAddedInterface;
use App\Shared\Application\Dto\DatesList;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Podczas tworzenia zadan pojedynczego utworzono takze alarmy pojedyncze
 */
class AlarmsAdded implements AlarmsAddedInterface
{
    private AlarmsGroupId $alarmsGroupId;
    private TasksGroupsList $taskGroup;
    private DatesList $dates;

    public function __construct(AlarmsGroupId $alarmsGroupId, TasksGroupsList $taskGroup, DatesList $dates)
    {
        $this->alarmsGroupId = $alarmsGroupId;
        $this->taskGroup = $taskGroup;
        $this->dates = $dates;
    }

    public function getAlarmGroupId(): AlarmsGroupId
    {
        return $this->alarmsGroupId;
    }

    public function getTaskGroup(): TasksGroupsList
    {
        return $this->taskGroup;
    }

    public function getDates(): DatesList
    {
        return $this->dates;
    }
}
