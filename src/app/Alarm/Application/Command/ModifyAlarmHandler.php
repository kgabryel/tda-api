<?php

namespace App\Alarm\Application\Command;

use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\Alarm;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;

abstract class ModifyAlarmHandler extends CommandHandler
{
    protected CommandBus $commandBus;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, CommandBus $commandBus)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->commandBus = $commandBus;
    }

    protected function getAlarm(string $alarmId): Alarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }

    protected function getSingleAlarm(AlarmId $alarmId): SingleAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }

    protected function getPeriodicAlarm(AlarmsGroupId $alarmId): PeriodicAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }
}
