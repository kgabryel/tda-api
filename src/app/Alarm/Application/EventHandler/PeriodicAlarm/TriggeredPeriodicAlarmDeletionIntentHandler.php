<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\PeriodicAlarm\Deleted;
use App\Alarm\Domain\Event\Port\TriggeredPeriodicAlarmDeletionIntent;
use App\Alarm\Domain\Event\Removed;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Task\Domain\Event\AlarmsModified;

#[ListenEvent(TriggeredPeriodicAlarmDeletionIntent::class)]
class TriggeredPeriodicAlarmDeletionIntentHandler
{
    private EventEmitter $eventEmitter;
    private QueryBus $queryBus;

    public function __construct(EventEmitter $eventEmitter, QueryBus $queryBus)
    {
        $this->eventEmitter = $eventEmitter;
        $this->queryBus = $queryBus;
    }

    public function handle(TriggeredPeriodicAlarmDeletionIntent $event): void
    {
        $alarm = $this->getPeriodicAlarm($event->getAlarmId());
        if (!$alarm->delete()) {
            return;
        }
        $alarms = $alarm->getAlarmsIds();
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarms));
        $this->eventEmitter->emit(new Removed($alarm));
        $this->eventEmitter->emit(new Deleted($alarm->getAlarmId()));
    }

    protected function getPeriodicAlarm(AlarmsGroupId $alarmId): PeriodicAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }
}
