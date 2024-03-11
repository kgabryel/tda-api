<?php

namespace App\Task\Domain\Event\SingleTask;

use App\Core\Cqrs\Event;
use App\Task\Domain\Entity\SingleTask;

/**
 * Zadanie pojedyncze zostalo zmodyfikowane, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
{
    private SingleTask $task;

    public function __construct(SingleTask $task)
    {
        $this->task = $task;
    }

    public function getTask(): SingleTask
    {
        return $this->task;
    }
}
