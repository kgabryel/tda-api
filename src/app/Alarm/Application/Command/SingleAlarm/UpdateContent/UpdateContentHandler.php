<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateContent;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;

class UpdateContentHandler extends ModifyAlarmHandler
{
    public function handle(UpdateContent $command): void
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        if ($alarm->updateContent($command->getContent())) {
            $this->eventEmitter->emit(new Updated($alarm));
        }
    }
}
