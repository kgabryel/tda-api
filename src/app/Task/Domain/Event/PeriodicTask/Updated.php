<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Core\Cqrs\Event;
use App\Task\Domain\Entity\PeriodicTask;

/**
 * Zadanie okresowe zostalo zmodyfikowane, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
{
    private PeriodicTask $task;

    public function __construct(PeriodicTask $task)
    {
        $this->task = $task;
    }

    public function getTask(): PeriodicTask
    {
        return $this->task;
    }
}
