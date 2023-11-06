<?php

namespace App\Alarm\Application\Command\SingleAlarm\Delete;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\AlarmsGroupsModified;
use App\Alarm\Domain\Event\Removed;
use App\Alarm\Domain\Event\SingleAlarm\Deleted;
use App\Alarm\Domain\Event\TasksModified;

class DeleteHandler extends ModifyAlarmHandler
{
    public function handle(Delete $command): void
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        $alarmGroupId = $alarm->getAlarmsGroupId();
        $taskId = $alarm->getTaskId();
        if (!$alarm->delete()) {
            return;
        }
        if ($alarmGroupId !== null) {
            $this->eventEmitter->emit(new AlarmsGroupsModified($alarm->getUserId(), $alarmGroupId));
        }
        if ($taskId !== null) {
            $this->eventEmitter->emit(new TasksModified($alarm->getUserId(), $taskId));
        }
        $this->eventEmitter->emit(new Removed($alarm));
        $this->eventEmitter->emit(new Deleted($alarm->getAlarmId()));
    }
}
