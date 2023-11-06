<?php

namespace App\Alarm\Application\Command\SingleAlarm\DeleteNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Check\Check;
use App\Alarm\Application\Command\SingleAlarm\Delete\Delete;
use App\Alarm\Application\DeleteResult;
use App\Alarm\Application\Query\FindByNotificationId\FindByNotificationId;
use App\Alarm\Domain\Entity\Notification;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Exception\AssignedAlarmModified;

class DeleteNotificationHandler extends ModifyAlarmHandler
{
    public function handle(DeleteNotification $command): DeleteResult
    {
        /** @var SingleAlarm $alarm */
        $alarm = $this->queryBus->handle(new FindByNotificationId($command->getNotificationId()));
        if ($alarm->hasGroup()) {
            throw new AssignedAlarmModified();
        }
        $notifications = $alarm->getNotifications();
        if (count($notifications) === 1) {
            $this->commandBus->handle(new Delete($alarm->getAlarmId()));

            return DeleteResult::DELETED;
        }
        /**
         * ilosc aktywnych powiadomien alarmu, z wykluczeniem aktualnie usuwanego
         */
        $uncheckedNotifications = count(
            array_filter(
                $notifications,
                static fn(Notification $notification) => self::isUnchecked($notification, $command)
            )
        );
        if ($uncheckedNotifications === 0) {
            $this->commandBus->handle(new Check($alarm->getAlarmId()));
        }
        $alarm->deleteNotification($command->getNotificationId());

        return DeleteResult::NOT_DELETED;
    }

    private static function isUnchecked(Notification $notification, DeleteNotification $command): bool
    {
        if ($notification->isChecked()) {
            return false;
        }

        return $notification->getNotificationId()->getValue() !== $command->getNotificationId()->getValue();
    }
}
