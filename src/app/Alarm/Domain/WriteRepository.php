<?php

namespace App\Alarm\Domain;

use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;
use DateTimeImmutable;

interface WriteRepository
{
    public function findSingleAlarmById(UserId $userId, AlarmId $alarmId): SingleAlarm;

    public function findById(UserId $userId, string $alarmId): SingleAlarm|PeriodicAlarm;

    public function findPeriodicAlarmById(UserId $userId, AlarmsGroupId $alarmId): PeriodicAlarm;

    public function findByDeactivationCode(UserId $userId, string $code): SingleAlarm;

    public function findByNotificationId(UserId $userId, NotificationId $notificationId): SingleAlarm;

    public function findByNotificationsGroupId(UserId $userId, NotificationsGroupId $notificationId): PeriodicAlarm;

    public function getNotificationsBetweenDates(DateTimeImmutable $start, DateTimeImmutable $stop): array;

    public function findAlarmsToCreate(DateTimeImmutable $date): array;
}
