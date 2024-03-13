<?php

namespace App\Alarm\Infrastructure\Repository;

use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\WriteRepository;
use App\Alarm\Infrastructure\Manager\AlarmManager;
use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\AlarmGroup;
use App\Alarm\Infrastructure\Model\Notification;
use App\Alarm\Infrastructure\Model\NotificationBuff;
use App\Core\Cache;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;

class AlarmsWriteRepository implements WriteRepository
{
    public function findSingleAlarmById(UserId $userId, AlarmId $alarmId): SingleAlarm
    {
        $aId = $alarmId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(AlarmManager::getCacheKey($alarmId), static function() use ($aId, $uId) {
            return Alarm::where('id', '=', $aId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }

    public function findPeriodicAlarmById(UserId $userId, AlarmsGroupId $alarmId): PeriodicAlarm
    {
        $aId = $alarmId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(AlarmManager::getCacheKey($alarmId), static function() use ($aId, $uId) {
            return AlarmGroup::where('id', '=', $aId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }

    public function findById(UserId $userId, string $alarmId): SingleAlarm|PeriodicAlarm
    {
        $uId = $userId->getValue();

        return Cache::remember(AlarmManager::getCacheKey($alarmId), static function() use ($alarmId, $uId) {
            $alarm = Alarm::where('id', '=', $alarmId)
                ->where('user_id', '=', $uId)
                ->first();
            if ($alarm === null) {
                $alarm = AlarmGroup::where('id', '=', $alarmId)
                    ->where('user_id', '=', $uId)
                    ->firstOrFail();
            }

            return $alarm->toDomainModel();
        });
    }

    public function findByDeactivationCode(UserId $userId, string $code): SingleAlarm
    {
        $uId = $userId->getValue();
        /** @var SingleAlarm $alarm */
        $alarm = Cache::remember(
            sprintf('alarms-code-%s-%s', $code, $uId),
            static function() use ($code, $uId) {
                return Alarm::where('deactivation_code', '=', $code)
                    ->where('user_id', '=', $uId)
                    ->firstOrFail()
                    ->toDomainModel();
            }
        );
        $key = AlarmManager::getCacheKey($alarm->getAlarmId());
        Cache::add($key, $alarm);

        return Cache::get($key);
    }

    public function findByNotificationId(UserId $userId, NotificationId $notificationId): SingleAlarm
    {
        $uId = $userId->getValue();
        $nId = $notificationId->getValue();
        /** @var SingleAlarm $alarm */
        $alarm = Cache::remember(
            sprintf('alarms-notification-%s-%s', $nId, $uId),
            static function() use ($nId, $uId) {
                return Alarm::select('alarms.*')
                    ->join('notifications', 'alarms.id', '=', 'notifications.alarm_id')
                    ->where('notifications.id', '=', $nId)
                    ->where('user_id', '=', $uId)
                    ->firstOrFail()
                    ->toDomainModel();
            }
        );
        $key = AlarmManager::getCacheKey($alarm->getAlarmId());
        Cache::add($key, $alarm);

        return Cache::get($key);
    }

    public function getNotificationsBetweenDates(DateTimeImmutable $start, DateTimeImmutable $stop): array
    {
        return Notification::select('notifications.*', 'alarms.user_id as userId')
            ->whereBetween('notifications.time', [$start, $stop])
            ->where('notifications.checked', '=', false)
            ->whereNull('notifications_buff.notification_id')
            ->join('alarms', 'alarms.id', '=', 'notifications.alarm_id')
            ->leftJoin('notifications_buff', 'notifications.id', '=', 'notifications_buff.notification_id')
            ->get()
            ->map(static fn(Notification $notification) => $notification->toNotificationTime())
            ->toArray();
    }

    public function findAlarmsToCreate(DateTimeImmutable $date): array
    {
        return AlarmGroup::whereNull('task_id')
            ->where('active', '=', true)
            ->where(function($query) use ($date) {
                $query->whereNull('stop')->orWhere('stop', '>=', $date);
            })
            ->get()
            ->map(static fn(AlarmGroup $alarmGroup) => $alarmGroup->toDomainModel())
            ->toArray();
    }

    public function getNotificationsToSend(): EloquentCollection
    {
        return NotificationBuff::select(
            'notifications_buff.*',
            'alarms.name',
            'alarms.content',
            'alarms.id as alarmId',
            'alarms.task_id as taskId',
            'alarms.group_id as groupId',
            'notifications_buff.time',
            'alarms.deactivationCode',
            'users.notificationEmail',
            'settings.notificationLang',
            DB::raw('users.email_verified_at is not null as emailAvailable'),
            'users.id as userId'
        )
            ->with(
                [
                    'notification.alarm.notifications' => static fn($query) => $query->where('time', '>', Carbon::now())
                        ->where('checked', '=', false),
                    'notification.alarm.notifications.notificationTypes',
                    'notification.notificationTypes'
                ]
            )
            ->join('notifications', 'notifications_buff.notification_id', '=', 'notifications.id')
            ->join('alarms', 'notifications.alarm_id', '=', 'alarms.id')
            ->join('users', 'alarms.user_id', '=', 'users.id')
            ->join('settings', 'users.id', '=', 'settings.user_id')
            ->where('notifications_buff.locked', '=', false)
            ->where('notifications_buff.time', '<=', Carbon::now())->get();
    }

    public function findByNotificationsGroupId(UserId $userId, NotificationsGroupId $notificationId): PeriodicAlarm
    {
        $uId = $userId->getValue();
        $nId = $notificationId->getValue();
        /** @var PeriodicAlarm $alarm */
        $alarm = Cache::remember(
            sprintf('alarms-notifications-group-%s-%s', $nId, $uId),
            static function() use ($nId, $uId) {
                return AlarmGroup::select('alarms_groups.*')
                    ->join('notifications_groups', 'alarms_groups.id', '=', 'notifications_groups.alarm_id')
                    ->where('notifications_groups.id', '=', $nId)
                    ->where('user_id', '=', $uId)
                    ->firstOrFail()
                    ->toDomainModel();
            }
        );
        $key = AlarmManager::getCacheKey($alarm->getAlarmId());
        Cache::add($key, $alarm);

        return Cache::get($key);
    }
}
