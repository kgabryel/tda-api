<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Command\PeriodicAlarm\Deactivate\Deactivate;
use App\Alarm\Application\Command\PeriodicAlarm\Deactivate\DeactivateAction;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\PeriodicAlarm\AlarmsGroupsModified;
use App\Alarm\Domain\Event\Port\TriggeredAlarmDeactivationIntent;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmsGroupId;

#[ListenEvent(TriggeredAlarmDeactivationIntent::class)]
class TriggeredAlarmDeactivationIntentHandler
{
    private CommandBus $commandBus;
    private EventEmitter $eventEmitter;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, EventEmitter $eventEmitter, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
        $this->queryBus = $queryBus;
    }

    public function handle(TriggeredAlarmDeactivationIntent $event): void
    {
        $alarm = $this->getAlarm($event->getAlarmId());
        $this->commandBus->handle(new Deactivate($event->getAlarmId(), DeactivateAction::from($event->getAction())));
        $this->eventEmitter->emit(new AlarmsGroupsModified($alarm->getUserId(), $alarm->getAlarmId()));
    }

    private function getAlarm(AlarmsGroupId $id): PeriodicAlarm
    {
        return $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
    }
}
