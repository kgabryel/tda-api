<?php

namespace App\Task\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;

/**
 * Zadanie zostalo utworzone, nalezy zmodyfikowac powiazane rzeczy
 */
class Created implements AsyncEvent
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
