<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateTask;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;
use App\Alarm\Domain\Event\TasksModified;
use App\Shared\Application\Dto\SingleTasksIdsList;

class UpdateTaskHandler extends ModifyAlarmHandler
{
    public function handle(UpdateTask $command): void
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        $modifiedTasks = new SingleTasksIdsList();
        if ($alarm->hasTask()) {
            $modifiedTasks->add($alarm->getTaskId());
        }
        if ($command->getTaskId() !== null) {
            $modifiedTasks->add($command->getTaskId());
        }
        if ($alarm->updateTask($command->getTaskId())) {
            $this->eventEmitter->emit(new Updated($alarm));
            $this->eventEmitter->emit(new TasksModified($alarm->getUserId(), ...$modifiedTasks->get()));
        }
    }
}
