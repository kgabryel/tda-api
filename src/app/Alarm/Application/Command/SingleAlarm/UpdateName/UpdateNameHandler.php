<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateName;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;

class UpdateNameHandler extends ModifyAlarmHandler
{
    public function handle(UpdateName $command): void
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        if ($alarm->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($alarm));
        }
    }
}
