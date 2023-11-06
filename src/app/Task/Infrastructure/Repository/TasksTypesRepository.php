<?php

namespace App\Task\Infrastructure\Repository;

use App\Shared\Application\TasksTypesCollection;
use App\Shared\Application\TasksTypesRepository as TasksTypesRepositoryInterface;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Infrastructure\Model\Task;
use Illuminate\Support\Collection;

class TasksTypesRepository implements TasksTypesRepositoryInterface
{
    public function getTasksTypes(string ...$ids): TasksTypesCollection
    {
        $collection = new TasksTypesCollection();

        /** @var Collection $tasks */
        $tasks = Task::whereIn('id', $ids)->pluck('id');
        foreach ($ids as $id) {
            if ($tasks->contains($id)) {
                $collection->addTask(new TaskId($id));
            } else {
                $collection->addTasksGroup(new TasksGroupId($id));
            }
        }

        return $collection;
    }
}
