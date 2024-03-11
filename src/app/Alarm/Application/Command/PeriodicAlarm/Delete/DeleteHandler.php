<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Delete;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\Deleted;
use App\Alarm\Domain\Event\Removed;
use App\Alarm\Domain\Event\TasksGroupsModified;
use App\Alarm\Domain\Event\TasksModified;
use App\Task\Domain\Event\AlarmsModified;

class DeleteHandler extends ModifyAlarmHandler
{
    public function handle(Delete $command): void
    {
        $alarm = $this->getPeriodicAlarm($command->getAlarmId());
        $alarms = $alarm->getAlarmsIds();
        $taskId = $alarm->getTaskId();
        $tasksIds = $alarm->getConnectedTasksIds();
        if (!$alarm->delete()) {
            return;
        }
        if (!$command->deleteAlarms()) {
            $alarm->disconnectAlarms();
        } else {
            $this->eventEmitter->emit(new TasksModified($alarm->getUserId(), ...$tasksIds));
        }
        if ($taskId !== null) {
            $this->eventEmitter->emit(new TasksGroupsModified($alarm->getUserId(), $taskId));
        }
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarms));
        $this->eventEmitter->emit(new Removed($alarm));
        $this->eventEmitter->emit(new Deleted($alarm->getAlarmId()));
    }
}
