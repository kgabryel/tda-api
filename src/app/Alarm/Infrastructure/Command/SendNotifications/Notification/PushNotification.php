<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications\Notification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Infrastructure\Command\SendNotifications\NotificationToSend;
use App\Shared\Application\Service\WebPushServiceInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\UserId;
use App\User\Infrastructure\Model\NotificationEndpoint;

class PushNotification implements NotificationInterface
{
    /** @var NotificationEndpoint[] */
    private array $endpoints;
    private NotificationToSend $notification;
    private WebPushServiceInterface $webPushService;

    public function __construct(NotificationToSend $notification, WebPushServiceInterface $webPushService)
    {
        $this->notification = $notification;
        $this->webPushService = $webPushService;
        $this->endpoints = NotificationEndpoint::where('user_id', '=', $notification->getUserId()->getValue())
            ->get()
            ->all();
    }

    public function getUserId(): UserId
    {
        return $this->notification->getUserId();
    }

    public function isAvailable(): bool
    {
        return $this->endpoints !== [];
    }

    public function send(): void
    {
        foreach ($this->endpoints as $endpoint) {
            $this->webPushService->addNotification(
                $endpoint->getEndpoint(),
                $endpoint->getAuth(),
                $endpoint->getP256dh(),
                $this->notification->getLang(),
                $this->notification->getName(),
                $this->notification->getContent(),
                $this->notification->getDeactivationCode(),
                new AlarmId($this->notification->getAlarmId())
            );
        }
        $this->webPushService->send();
    }

    public function getNotificationId(): NotificationId
    {
        return new NotificationId($this->notification->getId());
    }
}
