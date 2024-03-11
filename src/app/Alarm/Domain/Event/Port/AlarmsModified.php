<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy zostaly zmodyfikowane
 */
interface AlarmsModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
