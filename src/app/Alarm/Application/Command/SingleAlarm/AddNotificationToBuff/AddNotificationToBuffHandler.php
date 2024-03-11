<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddNotificationToBuff;

use App\Alarm\Application\NotificationManagerInterface;

class AddNotificationToBuffHandler
{
    private NotificationManagerInterface $notificationManager;

    public function __construct(NotificationManagerInterface $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }

    public function handle(AddNotificationToBuff $command): void
    {
        $this->notificationManager->addToBuff($command->getNotificationTime());
    }
}
