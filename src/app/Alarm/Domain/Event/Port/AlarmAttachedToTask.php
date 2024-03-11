<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;

/**
 * do pojedynczego zadania zostal przypisany alarm
 */
interface AlarmAttachedToTask extends Event
{
    public function getAlarmId(): AlarmId;

    public function getTaskId(): TaskId;
}
