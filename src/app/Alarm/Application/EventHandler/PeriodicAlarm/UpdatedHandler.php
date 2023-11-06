<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\EventHandler\EventHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Updated::class)]
class UpdatedHandler extends EventHandler
{
    public function handle(Updated $event): void
    {
        $this->alarmManager->updatePeriodicAlarm($event->getAlarm());
    }
}
