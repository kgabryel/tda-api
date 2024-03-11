<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications\Notification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Infrastructure\Command\SendNotifications\Notification;
use App\Alarm\Infrastructure\Command\SendNotifications\NotificationToSend;
use App\Shared\Domain\Entity\UserId;
use Illuminate\Support\Facades\Mail;

class EmailNotification implements NotificationInterface
{
    private NotificationToSend $notification;

    public function __construct(NotificationToSend $notification)
    {
        $this->notification = $notification;
    }

    public function isAvailable(): bool
    {
        return $this->notification->isEmailAvailable();
    }

    public function send(): void
    {
        $email = new Notification($this->notification);
        $email->subject($this->notification->getName());
        Mail::to($this->notification->getNotificationEmail())->send($email);
    }

    public function getNotificationId(): NotificationId
    {
        return new NotificationId($this->notification->getId());
    }

    public function getUserId(): UserId
    {
        return $this->notification->getUserId();
    }
}
