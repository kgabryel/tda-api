<?php

namespace App\Alarm\Infrastructure\Service;

use App\Alarm\Domain\Service\NotificationsService as NotificationsServiceInterface;
use App\Shared\Domain\Entity\AlarmId;
use Illuminate\Support\Facades\DB;

class NotificationsService implements NotificationsServiceInterface
{
    public function deleteNotificationsFromBuff(AlarmId $id): void
    {
        DB::table('notifications_buff')
            ->join('notifications', 'notifications_buff.notification_id', '=', 'notifications.id')
            ->join('alarms', 'notifications.alarm_id', '=', 'alarms.id')
            ->where('alarms.id', '=', $id->getValue())
            ->delete();
    }
}
