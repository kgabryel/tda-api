<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\UpdateContent;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;

class UpdateContentHandler extends ModifyAlarmHandler
{
    public function handle(UpdateContent $command): void
    {
        $alarm = $this->getPeriodicAlarm($command->getAlarmId());
        if ($alarm->updateContent($command->getContent())) {
            $this->eventEmitter->emit(new Updated($alarm));
            $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarm->getAlarmsIds()));
        }
    }
}
