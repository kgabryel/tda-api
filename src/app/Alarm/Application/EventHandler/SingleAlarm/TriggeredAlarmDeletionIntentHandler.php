<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\Command\SingleAlarm\Delete\Delete;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\Port\TriggeredAlarmDeletionIntent;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;

#[ListenEvent(TriggeredAlarmDeletionIntent::class)]
class TriggeredAlarmDeletionIntentHandler
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function handle(TriggeredAlarmDeletionIntent $event): void
    {
        $alarm = $this->getSingleAlarm($event->getAlarmId());
        $alarm->setTaskId(null);
        $this->commandBus->handle(new Delete($event->getAlarmId()));
    }

    protected function getSingleAlarm(AlarmId $alarmId): SingleAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }
}
