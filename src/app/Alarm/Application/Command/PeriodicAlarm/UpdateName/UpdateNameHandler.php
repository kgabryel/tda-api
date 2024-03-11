<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\UpdateName;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;

class UpdateNameHandler extends ModifyAlarmHandler
{
    public function handle(UpdateName $command): void
    {
        $alarm = $this->getPeriodicAlarm($command->getAlarmId());
        if ($alarm->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($alarm));
            $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarm->getAlarmsIds()));
        }
    }
}
