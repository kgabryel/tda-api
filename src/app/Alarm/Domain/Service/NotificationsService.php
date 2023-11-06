<?php

namespace App\Alarm\Domain\Service;

use App\Shared\Domain\Entity\AlarmId;

interface NotificationsService
{
    public function deleteNotificationsFromBuff(AlarmId $id): void;
}
