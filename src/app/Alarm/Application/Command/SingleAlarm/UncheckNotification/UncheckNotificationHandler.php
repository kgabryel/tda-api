<?php

namespace App\Alarm\Application\Command\SingleAlarm\UncheckNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Uncheck\Uncheck;
use App\Alarm\Application\Query\FindByNotificationId\FindByNotificationId;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\SingleAlarm\NotificationUnchecked;

class UncheckNotificationHandler extends ModifyAlarmHandler
{
    public function handle(UncheckNotification $command): bool
    {
        $notificationId = $command->getNotificationId();
        $alarm = $this->getSingleAlarmByNotificationId($notificationId);
        $notification = $alarm->getNotification($notificationId);
        if (!$notification->uncheck()) {
            return false;
        }
        if ($alarm->isChecked()) {
            $this->commandBus->handle(new Uncheck($alarm->getAlarmId()));
        }
        $this->eventEmitter->emit(new NotificationUnchecked($notification, $alarm->getUserId()));

        return true;
    }

    private function getSingleAlarmByNotificationId(NotificationId $notificationId): SingleAlarm
    {
        return $this->queryBus->handle(new FindByNotificationId($notificationId));
    }
}
