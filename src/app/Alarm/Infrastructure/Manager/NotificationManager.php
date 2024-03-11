<?php

namespace App\Alarm\Infrastructure\Manager;

use App\Alarm\Application\Command\SingleAlarm\Create\Notification as NotificationDto;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Domain\Entity\Notification as DomainModel;
use App\Alarm\Domain\Entity\NotificationGroup as GroupDomainModel;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\NotificationTime;
use App\Alarm\Domain\Entity\NotificationTypesList;
use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\Notification;
use App\Alarm\Infrastructure\Model\NotificationBuff;
use App\Alarm\Infrastructure\Model\NotificationGroup;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

class NotificationManager implements NotificationManagerInterface
{
    public function create(
        AlarmId $alarmId,
        UserId $userId,
        NotificationDto $time
    ): DomainModel {
        $checked = $time->getTime()->getTimestamp() <= time();
        $notification = new Notification();
        $notification->setTime($time->getTime())
            ->setAlarmId($alarmId)
            ->setChecked($checked);
        if ($time->getNotificationsGroupId() !== null) {
            $notification->setNotificationsGroupId($time->getNotificationsGroupId());
        }
        $notification->save();
        $notification->notificationTypes()->sync($time->getTypesList()->getIds());
        $domainModel = $notification->toDomainModel();
        if (!$checked) {
            $this->addToBuff(NotificationTime::fromNotification($domainModel));
        }

        return $domainModel;
    }

    public function addToBuff(NotificationTime $notification): void
    {
        $notificationBuff = new NotificationBuff();
        $notificationBuff->setTime($notification->getTime())
            ->setNotificationId($notification->getNotificationId())
            ->save();
    }

    public function check(NotificationId $id): void
    {
        $notification = Notification::find($id);
        $notification->setChecked(true)->update();
        NotificationBuff::where('notification_id', '=', $id->getValue())->delete();
    }

    public function uncheck(NotificationId $id): void
    {
        $notification = Notification::find($id);
        $notification->setChecked(false)->update();
    }

    public function createNotificationGroup(
        AlarmsGroupId $alarmId,
        int $time,
        NotificationTypesList $notificationTypesList,
        string $hour,
        string $intervalBehaviour,
        ?int $interval
    ): GroupDomainModel {
        $notification = new NotificationGroup();
        $notification->setTime($time)
            ->setAlarmId($alarmId)
            ->setHour($hour)
            ->setIntervalBehaviour($intervalBehaviour)
            ->setInterval($interval)
            ->save();
        $notification->notificationTypes()->sync($notificationTypesList->getIds());

        return $notification->toDomainModel();
    }

    public function deleteNotificationsGroup(NotificationsGroupId $notificationId, AlarmsGroupId $alarmId): void
    {
        Notification::where('group_id', '=', $notificationId->getValue())->delete();
        $notification = new NotificationGroup();
        $notification->id = $notificationId->getValue();
        $notification->exists = true;
        $notification->delete();
        Alarm::where('alarms.group_id', '=', $alarmId->getValue())
            ->whereNotIn(
                'alarms.id',
                Alarm::select('alarms.id')->where('alarms.group_id', '=', $alarmId->getValue())
                    ->where('notifications.checked', '=', false)
                    ->join('notifications', 'notifications.alarm_id', '=', 'alarms.id')
            )
            ->update(['checked' => true]);
    }
}
