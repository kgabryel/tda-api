<?php

namespace App\Shared\Application;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use Ds\Set;

class TasksTypesCollection
{
    private Set $tasks;

    private Set $tasksGroups;

    public function __construct()
    {
        $this->tasks = new Set();
        $this->tasksGroups = new Set();
    }

    public function addTask(TaskId $taskId): void
    {
        $this->tasks->add($taskId);
    }

    public function addTasksGroup(TasksGroupId $tasksGroupId): void
    {
        $this->tasksGroups->add($tasksGroupId);
    }

    public function getTasks(): Set
    {
        return $this->tasks;
    }

    public function getTasksGroups(): Set
    {
        return $this->tasksGroups;
    }
}
