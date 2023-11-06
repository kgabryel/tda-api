<?php

namespace App\Alarm\Application;

use App\Alarm\Application\Command\SingleAlarm\Create\Notification as NotificationDto;
use App\Alarm\Domain\Entity\Notification;
use App\Alarm\Domain\Entity\NotificationGroup;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\NotificationTime;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

interface NotificationManagerInterface
{
    public function create(
        AlarmId $alarmId,
        UserId $userId,
        NotificationDto $time
    ): Notification;

    public function createNotificationGroup(
        AlarmsGroupId $alarmId,
        int $time,
        NotificationTypesList $notificationTypesList,
        string $hour,
        string $intervalBehaviour,
        ?int $interval
    ): NotificationGroup;

    public function check(NotificationId $id): void;

    public function uncheck(NotificationId $id): void;

    public function addToBuff(NotificationTime $notification): void;

    public function deleteNotificationsGroup(NotificationsGroupId $notificationId, AlarmsGroupId $alarmId): void;
}
