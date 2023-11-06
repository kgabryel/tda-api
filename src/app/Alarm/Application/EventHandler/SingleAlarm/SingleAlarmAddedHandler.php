<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\Command\SingleAlarm\Create\Create;
use App\Alarm\Domain\Event\Port\SingleAlarmAdded;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(SingleAlarmAdded::class)]
class SingleAlarmAddedHandler
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(SingleAlarmAdded $event): void
    {
        $alarmDto = $event->getAlarmDto();
        $alarmDto->setTaskId($event->getTaskId());
        $this->commandBus->handle(new Create($alarmDto, $event->getNotifications()));
    }
}
