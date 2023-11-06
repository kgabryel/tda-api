<?php

namespace App\Alarm\Application\Command\SingleAlarm\CheckNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Check\Check;
use App\Alarm\Application\Query\FindByNotificationId\FindByNotificationId;
use App\Alarm\Domain\Entity\Notification;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\SingleAlarm\NotificationChecked;

class CheckNotificationHandler extends ModifyAlarmHandler
{
    public function handle(CheckNotification $command): void
    {
        $alarm = $this->getSingleAlarmByNotificationId($command->getNotificationId());
        $notificationId = $command->getNotificationId();
        $notifications = $alarm->getNotifications();
        $hasUncheckedNotification = false;
        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            if ($notification->getNotificationId()->getValue() === $notificationId->getValue()) {
                $notification->check();
                $this->eventEmitter->emit(new NotificationChecked($notificationId));
            }
            if (!$notification->isChecked()) {
                $hasUncheckedNotification = true;
            }
        }
        if (!$hasUncheckedNotification) {
            $this->commandBus->handle(new Check($alarm->getAlarmId()));
        }
    }

    private function getSingleAlarmByNotificationId(NotificationId $notificationId): SingleAlarm
    {
        return $this->queryBus->handle(new FindByNotificationId($notificationId));
    }
}
