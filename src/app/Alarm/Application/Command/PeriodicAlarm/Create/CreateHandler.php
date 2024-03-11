<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Alarm\Application\AlarmManagerInterface;
use App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm\CreateAlarmsForPeriodicAlarm;
use App\Alarm\Application\Dto\NotificationsGroupsList;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Application\Utils\TimeUtils;
use App\Alarm\Domain\Event\Created;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\DateService;

//moze powinna byc osobna komenda do tworzenia alarmu, ktory jest przypisany do zadania
class CreateHandler extends AssignedUserCommandHandler
{
    private AlarmManagerInterface $alarmManager;
    private NotificationManagerInterface $notificationManager;
    private CommandBus $commandBus;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        AlarmManagerInterface $alarmManager,
        NotificationManagerInterface $notificationManager,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->alarmManager = $alarmManager;
        $this->notificationManager = $notificationManager;
        $this->commandBus = $commandBus;
    }

    public function handle(Create $command): void
    {
        $alarmDto = $command->getAlarm();
        if ($command->getTaskGroup() !== null) {
            $alarmDto->setTasksGroupId($command->getTaskGroup()->getTasksGroupId());
        }
        /**
         * lista dat, dla ktorych maja zostac utworzone alarmy pojedyncze, maksymalnie do konca nastepnego miesiaca
         */
        $dates = DateService::getDatesInRange(
            $alarmDto->getStart(),
            DateService::getNextNthMonthEnd(1),
            $alarmDto->getInterval(),
            $alarmDto->getIntervalType(),
            null,
            $command->getAlarm()->getStop()
        );
        $alarmGroup = $this->alarmManager->createPeriodicAlarm(
            $alarmDto->getAlarmId(),
            $alarmDto->getName(),
            $alarmDto->getContent(),
            $alarmDto->getCatalogsList(),
            $this->userId,
            $alarmDto->getStart(),
            $alarmDto->getStop(),
            $alarmDto->getInterval(),
            $alarmDto->getIntervalType()->value,
            $alarmDto->getTasksGroupId()
        );
        $groups = new NotificationsGroupsList();
        foreach ($command->getNotifications()->getNotifications() as $notification) {
            $groups->add(
                $this->notificationManager->createNotificationGroup(
                    $alarmGroup->getAlarmId(),
                    TimeUtils::roundToFullMinutes($notification->getTime()),
                    $notification->getTypesList(),
                    $notification->getHour(),
                    $notification->getIntervalBehaviour(),
                    $notification->getInterval()
                )
            );
        }
        $this->commandBus->handle(
            CreateAlarmsForPeriodicAlarm::fromPeriodicAlarm($dates, $alarmGroup, $command->getTaskGroup(), $groups)
        );
        $this->eventEmitter->emit(new Created($alarmGroup));
    }
}
