<?php

namespace App\Task\Domain\Event\SingleTask;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\TaskId;

/**
 * Zadanie pojedyncze zostalo usuniete, nalezy usunac je z bazy danych
 */
class Deleted implements Event
{
    private TaskId $taskId;

    public function __construct(TaskId $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}
