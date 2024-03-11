<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\Command\SingleAlarm\UpdateTask\UpdateTask;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\Port\AlarmDetachedFromTask;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;

#[ListenEvent(AlarmDetachedFromTask::class)]
class DetachedFromTaskHandler
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function getSingleAlarm(AlarmId $alarmId): SingleAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }

    public function handle(AlarmDetachedFromTask $event): void
    {
        $this->commandBus->handle(new UpdateTask($event->getAlarmId(), null));
    }
}
