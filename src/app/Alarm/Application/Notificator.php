<?php

namespace App\Alarm\Application;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function alarmsChanges(UserId|int $user, AlarmId|AlarmsGroupId ...$ids): void;
}
