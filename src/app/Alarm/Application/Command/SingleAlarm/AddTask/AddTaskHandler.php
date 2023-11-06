<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddTask;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;

class AddTaskHandler extends ModifyAlarmHandler
{
    public function handle(AddTask $command): void
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        if ($alarm->updateTask($command->getTaskId())) {
            $this->eventEmitter->emit(new Updated($alarm));
        }
    }
}
