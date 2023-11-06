<?php

namespace App\Alarm\Application\Command\SingleAlarm\CheckNotification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Dezaktywuje powiadomienie
 */
#[CommandHandler(CheckNotificationHandler::class)]
class CheckNotification implements Command
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
