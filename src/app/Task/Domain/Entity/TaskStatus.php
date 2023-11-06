<?php

namespace App\Task\Domain\Entity;

use App\Task\Domain\TaskStatus as TaskStatusName;

class TaskStatus
{
    private StatusId $id;
    private TaskStatusName $name;

    public function __construct(StatusId $id, TaskStatusName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): StatusId
    {
        return $this->id;
    }

    public function getName(): TaskStatusName
    {
        return $this->name;
    }
}
