<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\EventHandler\EventHandler;
use App\Alarm\Domain\Event\SingleAlarm\Deleted;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(Deleted::class)]
class DeletedHandler extends EventHandler
{
    public function handle(Deleted $event): void
    {
        $this->alarmManager->deleteSingleAlarm($event->getAlarmId());
    }
}
