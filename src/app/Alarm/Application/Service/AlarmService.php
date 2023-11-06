<?php

namespace App\Alarm\Application\Service;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Create;
use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;
use App\Alarm\Application\Dto\NotificationsList;
use App\Alarm\Domain\Entity\NotificationGroup;
use App\Core\Cqrs\CommandBus;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;
use DateTimeImmutable;

class AlarmService
{
    private UuidInterface $uuid;
    private CommandBus $commandBus;

    public function __construct(UuidInterface $uuid, CommandBus $commandBus)
    {
        $this->uuid = $uuid;
        $this->commandBus = $commandBus;
    }

    public function createForPeriodicAlarm(CreateData $createDataDto): AlarmId
    {
        $id = $createDataDto->getAlarmId() ?? new AlarmId($this->uuid->getValue());
        $alarmDto = new AlarmDto(
            $id,
            $createDataDto->getName(),
            $createDataDto->getContent(),
            new CatalogsIdsList()
        );
        if ($createDataDto->getTaskId() !== null) {
            $alarmDto->setTaskId($createDataDto->getTaskId());
        }
        $alarmDto->setDate($createDataDto->getDate());
        $alarmDto->setAlarmsGroupId($createDataDto->getAlarmsGroupId());
        $times = AlarmService::mapGroupsToNotifications($createDataDto->getGroups(), $createDataDto->getDate());
        $notifications = new Notifications($id, ...$times->get());
        $this->commandBus->handle(new Create($alarmDto, $notifications, true));

        return $id;
    }

    public static function mapGroupsToNotifications(array $groups, DateTimeImmutable $date): NotificationsList
    {
        $times = new NotificationsList();
        /** @var NotificationGroup $group */
        foreach ($groups as $group) {
            $times->addNotification(
                new Notification(
                    $date->modify(sprintf('%s seconds', $group->getTime())),
                    $group->getNotificationTypesList(),
                    $group->getNotificationId()
                )
            );
        }

        return $times;
    }
}
