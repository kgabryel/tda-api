<?php

namespace App\Alarm\Application\Command\SingleAlarm\Check;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\SingleAlarm\Updated;
use App\Alarm\Domain\Service\NotificationsService;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;

class CheckHandler extends ModifyAlarmHandler
{
    private NotificationsService $notificationsService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        NotificationsService $notificationsService
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->notificationsService = $notificationsService;
    }

    public function handle(Check $command): bool
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());
        if (!$alarm->check()) {
            return false;
        }
        $this->eventEmitter->emit(new Updated($alarm));
        $this->notificationsService->deleteNotificationsFromBuff($alarm->getAlarmId());
        return true;
    }
}
