<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\EventHandler\EventHandler;
use App\Alarm\Domain\Event\PeriodicAlarm\Deleted;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->alarmManager->deletePeriodicAlarm($event->getAlarmId());
    }
}
