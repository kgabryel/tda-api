<?php

namespace App\Alarm\Infrastructure\Repository;

use App\Alarm\Application\ReadRepository;
use App\Alarm\Application\ViewModel\PeriodicAlarm;
use App\Alarm\Application\ViewModel\SingleAlarm;
use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\AlarmGroup;
use App\Shared\Application\AlarmsTypesCollection;
use App\Shared\Application\AlarmsTypesRepository;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;
use Illuminate\Support\Collection;

class AlarmsReadRepository implements ReadRepository, AlarmsTypesRepository
{
    public function findAll(UserId $userId): array
    {
        return $this->parseAlarms(
            Alarm::with([
                'catalogs:id',
                'notifications' => static fn($query) => $query->orderBy('time', 'asc'),
                'notifications.notificationTypes:id'
            ])
                ->where('user_id', '=', $userId)
                ->orderBy('created_at', 'desc')->get(),
            AlarmGroup::with([
                'catalogs:id',
                'alarms' => static fn($query) => $query->orderBy('date', 'desc'),
                'notificationsGroups' => static fn($query) => $query->orderBy('time', 'asc'),
                'notificationsGroups.notificationTypes:id'
            ])
                ->where('user_id', '=', $userId)
                ->orderBy('created_at', 'desc')->get()
        )->toArray();
    }

    private function parseAlarms(Collection $alarms, Collection $alarmsGroups): Collection
    {
        return $alarms->concat($alarmsGroups)
            ->sort(
                static fn(Alarm|AlarmGroup $a, Alarm|AlarmGroup $b) => $a->getCreatedAt()->timestamp <=>
                    $b->getCreatedAt()->timestamp
            )
            ->values()
            ->map(fn(Alarm|AlarmGroup $alarm) => $alarm->toViewModel());
    }

    public function find(UserId $userId, string ...$ids): array
    {
        $alarms = Alarm::with([
            'catalogs:id',
            'notifications' => static fn($query) => $query->orderBy('time', 'asc'),
            'notifications.notificationTypes:id'
        ])
            ->where('user_id', '=', $userId)
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'desc')->get();
        $alarmsGroups =
            AlarmGroup::with(['catalogs:id', 'alarms' => static fn($query) => $query->orderBy('date', 'desc')])
                ->where('user_id', '=', $userId)
                ->whereIn('id', $ids)
                ->orderBy('created_at', 'desc')->get();

        return $this->parseAlarms($alarms, $alarmsGroups)->toArray();
    }

    public function getAlarmsTypes(string ...$ids): AlarmsTypesCollection
    {
        $collection = new AlarmsTypesCollection();

        /** @var Collection $alarms */
        $alarms = Alarm::whereIn('id', $ids)->pluck('id');
        foreach ($ids as $id) {
            if ($alarms->contains($id)) {
                $collection->addAlarm(new AlarmId($id));
            } else {
                $collection->addAlarmsGroup(new AlarmsGroupId($id));
            }
        }

        return $collection;
    }

    public function findSingleAlarmById(UserId $userId, AlarmId $alarmId): SingleAlarm
    {
        return Alarm::with([
            'catalogs:id',
            'notifications' => static fn($query) => $query->orderBy('time', 'asc'),
            'notifications.notificationTypes:id'
        ])
            ->where('id', '=', $alarmId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function findPeriodicAlarmById(UserId $userId, AlarmsGroupId $alarmId): PeriodicAlarm
    {
        return AlarmGroup::with([
            'catalogs:id',
            'alarms' => static fn($query) => $query->orderBy('date', 'desc'),
            'notificationsGroups' => static fn($query) => $query->orderBy('time', 'asc'),
        ])
            ->where('id', '=', $alarmId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function findById(UserId $userId, string $alarmId): SingleAlarm|PeriodicAlarm
    {
        $alarm = Alarm::with([
            'catalogs:id',
            'notifications' => static fn($query) => $query->orderBy('time', 'asc'),
            'notifications.notificationTypes:id'
        ])
            ->where('id', '=', $alarmId)
            ->where('user_id', '=', $userId)
            ->first();
        if ($alarm === null) {
            $alarm = AlarmGroup::with([
                'catalogs:id',
                'alarms' => static fn($query) => $query->orderBy('date', 'desc'),
                'notificationsGroups' => static fn($query) => $query->orderBy('time', 'asc'),
            ])
                ->where('id', '=', $alarmId)
                ->where('user_id', '=', $userId)
                ->firstOrFail();
        }

        return $alarm->toViewModel();
    }
}
