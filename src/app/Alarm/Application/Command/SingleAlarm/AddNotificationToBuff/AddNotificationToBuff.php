<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddNotificationToBuff;

use App\Alarm\Domain\Entity\NotificationTime;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Dodaje powiadomienie do bufora
 */
#[CommandHandler(AddNotificationToBuffHandler::class)]
class AddNotificationToBuff implements Command
{
    private NotificationTime $notification;

    public function __construct(NotificationTime $notification)
    {
        $this->notification = $notification;
    }

    public function getNotificationTime(): NotificationTime
    {
        return $this->notification;
    }
}
