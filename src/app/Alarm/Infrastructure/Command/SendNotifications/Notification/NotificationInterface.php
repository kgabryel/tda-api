<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications\Notification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Shared\Domain\Entity\UserId;

interface NotificationInterface
{
    public function isAvailable(): bool;

    public function send(): void;

    public function getNotificationId(): NotificationId;

    public function getUserId(): UserId;
}
