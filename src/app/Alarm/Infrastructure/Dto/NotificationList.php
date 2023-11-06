<?php

namespace App\Alarm\Infrastructure\Dto;

use App\Alarm\Domain\Entity\Notification as NotificationEntity;
use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsList as NotificationsListInterface;
use App\Alarm\Infrastructure\Model\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationList implements NotificationsListInterface
{
    private HasMany $notifications;

    public function __construct(HasMany $notifications)
    {
        $this->notifications = $notifications;
    }

    public function check(): void
    {
        $this->notifications->update(['checked' => true]);
    }

    public function get(): array
    {
        return $this->notifications->getQuery()->get()
            ->map(static fn(Notification $notification) => $notification->toDomainModel())
            ->toArray();
    }

    public function delete(NotificationId $id): void
    {
        $this->notifications->clone()->where('notifications.id', '=', $id->getValue())->delete();
    }

    public function find(NotificationId $id): NotificationEntity
    {
        return $this->notifications->clone()->findOrFail($id->getValue())->toDomainModel();
    }
}
