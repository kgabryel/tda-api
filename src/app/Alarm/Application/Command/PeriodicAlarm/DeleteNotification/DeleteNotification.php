<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\DeleteNotification;

use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Usuwa powiadomienie
 */
#[CommandHandler(DeleteNotificationHandler::class)]
class DeleteNotification implements Command
{
    private NotificationsGroupId $notificationId;

    public function __construct(NotificationsGroupId $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): NotificationsGroupId
    {
        return $this->notificationId;
    }
}
