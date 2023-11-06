<?php

namespace App\Alarm\Application\Command\SingleAlarm\Uncheck;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\UncheckNotification\UncheckNotification;
use App\Alarm\Domain\Entity\Notification;
use App\Alarm\Domain\Event\SingleAlarm\Updated;

class UncheckHandler extends ModifyAlarmHandler
{
    public function handle(Uncheck $command): bool
    {
        $alarm = $this->getSingleAlarm($command->getAlarmId());

        $notifications = $alarm->getNotifications();
        $unchecked = [];
        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            if (!$notification->isChecked()) {
                continue;
            }
            if ($notification->getTime()->getTimestamp() <= time()) {
                continue;
            }
            $unchecked[] = $notification;
        }
        if ($unchecked === []) {
            return false;
        }
        if (!$alarm->uncheck()) {
            return false;
        }
        /** @var Notification $notification */
        foreach ($unchecked as $notification) {
            $this->commandBus->handle(new UncheckNotification($notification->getNotificationId()));
        }
        $this->eventEmitter->emit(new Updated($alarm));

        return true;
    }
}
