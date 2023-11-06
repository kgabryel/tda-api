<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\Command\SingleAlarm\CheckWithNotification\CheckWithNotification;
use App\Alarm\Domain\Event\Port\TriggeredAlarmCheckedIntent;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(TriggeredAlarmCheckedIntent::class)]
class TriggeredAlarmCheckedIntentHandler
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(TriggeredAlarmCheckedIntent $event): void
    {
        $this->commandBus->handle(new CheckWithNotification($event->getAlarmId()));
    }
}
