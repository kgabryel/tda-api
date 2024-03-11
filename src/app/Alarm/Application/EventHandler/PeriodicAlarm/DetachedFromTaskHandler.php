<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Command\SingleAlarm\UpdateTask\UpdateTask;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\Port\PeriodicAlarmDetachedFromTask;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmsGroupId;

#[ListenEvent(PeriodicAlarmDetachedFromTask::class)]
class DetachedFromTaskHandler
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function handle(PeriodicAlarmDetachedFromTask $event): void
    {
        $alarm = $this->getPeriodicAlarm($event->getAlarmId());
        foreach ($alarm->getAlarmsIds() as $id) {
            $this->commandBus->handle(new UpdateTask($id, null));
        }
    }

    private function getPeriodicAlarm(AlarmsGroupId $id): PeriodicAlarm
    {
        return $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
    }
}
