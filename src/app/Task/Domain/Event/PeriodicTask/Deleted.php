<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\TasksGroupId;

/**
 * Zadanie okresowe zostalo usuniete, nalezy usunac je z bazy danych
 */
class Deleted implements Event
{
    private TasksGroupId $taskId;

    public function __construct(TasksGroupId $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): TasksGroupId
    {
        return $this->taskId;
    }
}
