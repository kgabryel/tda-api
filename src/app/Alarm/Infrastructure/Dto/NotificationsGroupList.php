<?php

namespace App\Alarm\Infrastructure\Dto;

use App\Alarm\Domain\Entity\NotificationsGroupsList;
use App\Alarm\Infrastructure\Model\NotificationGroup;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationsGroupList implements NotificationsGroupsList
{
    private HasMany $notifications;

    public function __construct(HasMany $notifications)
    {
        $this->notifications = $notifications;
    }

    public function get(): array
    {
        return $this->notifications->get()
            ->map(static fn(NotificationGroup $notificationGroup) => $notificationGroup->toDomainModel())
            ->toArray();
    }
}
