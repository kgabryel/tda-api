<?php

namespace App\Task\Application\Command\SingleTask\UpdateAlarm;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\AlarmAttachedToTask;
use App\Task\Domain\Event\AlarmDetachedFromTask;

class UpdateAlarmHandler extends ModifyTaskHandler
{
    public function handle(UpdateAlarm $command): void
    {
        $task = $this->getSingleTask($command->getTaskId());
        $oldAlarmId = $task->getAlarmId();
        $newAlarmId = $command->getAlarmId();
        if (!$task->updateAlarm($newAlarmId)) {
            return;
        }

        if ($oldAlarmId !== null) {
            $this->eventEmitter->emit(new AlarmDetachedFromTask($oldAlarmId));
        }
        if ($newAlarmId !== null) {
            $this->eventEmitter->emit(new AlarmAttachedToTask($task->getTaskId(), $newAlarmId));
        }
    }
}
