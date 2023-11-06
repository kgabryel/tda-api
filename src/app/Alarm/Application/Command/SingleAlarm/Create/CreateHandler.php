<?php

namespace App\Alarm\Application\Command\SingleAlarm\Create;

use App\Alarm\Application\AlarmManagerInterface;
use App\Alarm\Application\Command\SingleAlarm\AddNotification\AddNotification;
use App\Alarm\Application\Command\SingleAlarm\AddNotification\NotificationDto;
use App\Alarm\Domain\Event\Created;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\UuidInterface;

class CreateHandler extends AssignedUserCommandHandler
{
    private AlarmManagerInterface $alarmManager;
    private UuidInterface $uuid;
    private CommandBus $commandBus;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        AlarmManagerInterface $alarmManager,
        UuidInterface $uuid,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->alarmManager = $alarmManager;
        $this->uuid = $uuid;
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
    }

    public function handle(Create $command): void
    {
        $alarmDto = $command->getAlarm();
        $alarm = $this->alarmManager->createSingleAlarm(
            $alarmDto,
            $this->userId,
            $this->uuid->getValue()
        );
        $notificationsDto = $command->getNotifications();
        foreach ($notificationsDto->getNotifications() as $time) {
            $this->commandBus->handle(
                new AddNotification(
                    new NotificationDto($notificationsDto->getAlarmId(), $time),
                    $command->isFromGroup()
                )
            );
        }
        $this->eventEmitter->emit(new Created($alarm));
    }
}
