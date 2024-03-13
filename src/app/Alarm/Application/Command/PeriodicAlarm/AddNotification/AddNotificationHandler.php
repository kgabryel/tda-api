<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\AddNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\AddNotification\AddNotification as AddSingleAlarmNotification;
use App\Alarm\Application\Command\SingleAlarm\AddNotification\NotificationDto as SingleAlarmNotificationDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Application\Utils\TimeUtils;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use DateTimeImmutable;

class AddNotificationHandler extends ModifyAlarmHandler
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

    public function handle(AddNotification $command): void
    {
        $alarm = $this->getPeriodicAlarm($command->getAlarmId());
        $notification = $command->getNotification();
        $time = TimeUtils::roundToFullMinutes($notification->getTime());
        $notificationGroup = $this->notificationManager->createNotificationGroup(
            $command->getAlarmId(),
            $time,
            $notification->getTypesList(),
            $notification->getHour(),
            $notification->getIntervalBehaviour(),
            $notification->getInterval()
        );
        $alarms = $alarm->getAlarmsIds();
        foreach ($alarms as $alarmId) {
            $singleAlarm = $this->getSingleAlarm($alarmId);
            /** @var DateTimeImmutable $notificationTime */
            $notificationTime = $singleAlarm->getDate()?->modify(sprintf('%s seconds', $time));
            $this->commandBus->handle(
                new AddSingleAlarmNotification(
                    new SingleAlarmNotificationDto(
                        $alarmId,
                        new Notification(
                            $notificationTime,
                            $notification->getTypesList(),
                            $notificationGroup->getNotificationId()
                        )
                    ),
                    true
                )
            );
        }
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarms));
    }
}
