<?php

namespace App\Alarm\Application\Command\SingleAlarm\UncheckNotification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Aktywuje powiadomienie
 */
#[CommandHandler(UncheckNotificationHandler::class)]
class UncheckNotification implements Command
{
    private NotificationId $notificationId;

    public function __construct(NotificationId $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }
}
