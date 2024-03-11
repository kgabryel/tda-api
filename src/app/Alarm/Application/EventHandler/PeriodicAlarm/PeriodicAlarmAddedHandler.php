<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Command\PeriodicAlarm\Create\Create;
use App\Alarm\Domain\Event\PeriodicAlarm\AlarmsGroupsModified;
use App\Alarm\Domain\Event\Port\PeriodicAlarmAdded;
use App\Core\BusUtils;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(PeriodicAlarmAdded::class)]
class PeriodicAlarmAddedHandler
{
    private CommandBus $commandBus;
    private EventEmitter $eventEmitter;
    private BusUtils $busUtils;

    public function __construct(CommandBus $commandBus, EventEmitter $eventEmitter, BusUtils $busUtils)
    {
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
        $this->busUtils = $busUtils;
    }

    public function handle(PeriodicAlarmAdded $event): void
    {
        $this->commandBus->handle(new Create($event->getAlarm(), $event->getNotifications(), $event->getTaskGroup()));
        $this->eventEmitter->emit(
            new AlarmsGroupsModified($this->busUtils->getUserId(), $event->getAlarm()->getAlarmId())
        );
    }
}
