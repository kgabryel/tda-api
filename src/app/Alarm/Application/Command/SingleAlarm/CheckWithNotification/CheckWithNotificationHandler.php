<?php

namespace App\Alarm\Application\Command\SingleAlarm\CheckWithNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Check\Check;
use App\Alarm\Application\Command\SingleAlarm\Check\CheckHandler;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;

class CheckWithNotificationHandler extends ModifyAlarmHandler
{
    private CheckHandler $checkHandler;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        CheckHandler $checkHandler
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->checkHandler = $checkHandler;
    }

    public function handle(CheckWithNotification $command): bool
    {
        $result = $this->checkHandler->handle(new Check($command->getAlarmId()));
        if ($result === false) {
            return false;
        }
        /** @var SingleAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindById($command->getAlarmId(), QueryResult::DOMAIN_MODEL));
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), $alarm->getAlarmId()));

        return true;
    }
}
