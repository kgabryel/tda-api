<?php

namespace App\Alarm\Application;

use App\Alarm\Application\ViewModel\PeriodicAlarm;
use App\Alarm\Application\ViewModel\SingleAlarm;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findSingleAlarmById(UserId $userId, AlarmId $alarmId): SingleAlarm;

    public function findById(UserId $userId, string $alarmId): SingleAlarm|PeriodicAlarm;

    public function findPeriodicAlarmById(UserId $userId, AlarmsGroupId $alarmId): PeriodicAlarm;

    public function find(UserId $userId, string ...$alarmsIds): array;

    public function findAll(UserId $userId): array;
}
