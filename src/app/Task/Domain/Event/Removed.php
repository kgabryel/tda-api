<?php

namespace App\Task\Domain\Event;

use App\Core\Cqrs\Event;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;

/**
 * Zadanie zostalo usuniete, nalezy zmodyfikowac powiazane rzeczy
 */
class Removed implements Event
{
    private SingleTask|PeriodicTask $task;

    public function __construct(SingleTask|PeriodicTask $task)
    {
        $this->task = $task;
    }

    public function getTask(): SingleTask|PeriodicTask
    {
        return $this->task;
    }
}
