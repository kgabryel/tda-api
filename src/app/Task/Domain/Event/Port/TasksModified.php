<?php

namespace App\Task\Domain\Event\Port;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane zadania pojedyncze zostaly zmodyfikowane przez przypiecie lub odpiecie czegos do zadania
 */
interface TasksModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
