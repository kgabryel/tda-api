<?php

namespace App\Shared\Infrastructure;

use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Alarm\Infrastructure\Model\NotificationsType;
use App\User\Infrastructure\Model\User;

abstract class NotificationTypesUtils
{
    public static function getTypesIdsForUser(User $user): array
    {
        if ($user->hasVerifiedEmail()) {
            $notificationQuery = NotificationsType::all();
        } else {
            $notificationQuery = NotificationsType::where('name', '!=', NotificationTypeValue::EMAIL->value);
        }

        return $notificationQuery->pluck('id')->toArray();
    }
}
