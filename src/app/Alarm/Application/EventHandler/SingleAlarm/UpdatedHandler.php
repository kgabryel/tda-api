<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\EventHandler\EventHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->alarmManager->updateSingleAlarm($event->getAlarm());
    }
}
