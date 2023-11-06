<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\DeleteNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\PeriodicAlarm\Delete\Delete;
use App\Alarm\Application\DeleteResult;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Application\Query\FindByNotificationsGroupId\FindByNotificationsGroupId;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;

class DeleteNotificationHandler extends ModifyAlarmHandler
{
    private NotificationManagerInterface $notificationManager;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        NotificationManagerInterface $notificationManager
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->notificationManager = $notificationManager;
    }

    public function handle(DeleteNotification $command): DeleteResult
    {
        /** @var PeriodicAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindByNotificationsGroupId($command->getNotificationId()));
        $notifications = $alarm->getNotificationsGroups();
        if (count($notifications) === 1) {
            $this->commandBus->handle(new Delete($alarm->getAlarmId(), true));

            return DeleteResult::DELETED;
        }
        $alarms = $alarm->getAlarmsIds();
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarms));
        $this->notificationManager->deleteNotificationsGroup($command->getNotificationId(), $alarm->getAlarmId());

        return DeleteResult::NOT_DELETED;
    }
}
