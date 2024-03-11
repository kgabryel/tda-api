<?php

namespace App\Task\Infrastructure\Repository;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Application\ReadRepository;
use App\Task\Application\ViewModel\PeriodicTask;
use App\Task\Application\ViewModel\SingleTask;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use Illuminate\Support\Collection;

class TasksReadRepository implements ReadRepository
{
    public function findSingleTaskById(UserId $userId, TaskId $taskId): SingleTask
    {
        return Task::with([
            'catalogs:id',
            'files:id',
            'bookmarks:id',
            'notes:id',
            'videos:id',
            'subtasks' => static fn($query) => $query->orderBy('date', 'desc')
        ])
            ->select('tasks.*', 'alarms.id as alarmId')
            ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
            ->where('tasks.id', '=', $taskId)
            ->where('tasks.user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function findById(UserId $userId, string $taskId): SingleTask|PeriodicTask
    {
        $task = Task::with(
            [
                'catalogs:id',
                'files:id',
                'bookmarks:id',
                'notes:id',
                'videos:id',
                'subtasks' => static fn($query) => $query->orderBy('date', 'desc')
            ]
        )
            ->select('tasks.*', 'alarms.id as alarmId')
            ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
            ->where('tasks.id', '=', $taskId)
            ->where('tasks.user_id', '=', $userId)
            ->first();
        if ($task === null) {
            $task = TaskGroup::with([
                'catalogs:id',
                'files:id',
                'bookmarks:id',
                'notes:id',
                'videos:id',
                'tasks' => static fn($query) => $query->orderBy('date', 'desc')
            ])
                ->select('tasks_groups.*', 'alarms_groups.id as alarmId')
                ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
                ->where('tasks_groups.id', '=', $taskId)
                ->where('tasks_groups.user_id', '=', $userId)
                ->firstOrFail();
        }

        return $task->toViewModel();
    }

    public function findPeriodicTaskById(UserId $userId, TasksGroupId $taskId): PeriodicTask
    {
        return TaskGroup::with(
            [
                'catalogs:id',
                'files:id',
                'bookmarks:id',
                'notes:id',
                'videos:id',
                'tasks' => static fn($query) => $query->orderBy('date', 'desc')
            ]
        )
            ->select('tasks_groups.*', 'alarms_groups.id as alarmId')
            ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
            ->where('tasks_groups.id', '=', $taskId)
            ->where('tasks_groups.user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, string ...$tasksIds): array
    {
        return $this->parseTasks(
            Task::with(
                [
                    'catalogs:id',
                    'files:id',
                    'bookmarks:id',
                    'notes:id',
                    'videos:id',
                    'subtasks' => static fn($query) => $query->orderBy('date', 'desc')
                ]
            )
                ->select('tasks.*', 'alarms.id as alarmId')
                ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
                ->where('tasks.user_id', '=', $userId)
                ->whereIn('tasks.id', $tasksIds)
                ->orderBy('created_at', 'desc')->get(),
            TaskGroup::with(
                [
                    'catalogs:id',
                    'files:id',
                    'bookmarks:id',
                    'notes:id',
                    'videos:id',
                    'tasks' => static fn($query) => $query->orderBy('date', 'desc')
                ]
            )
                ->select('tasks_groups.*', 'alarms_groups.id as alarmId')
                ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
                ->where('tasks_groups.user_id', '=', $userId)
                ->whereIn('tasks_groups.id', $tasksIds)
                ->orderBy('created_at', 'desc')->get()
        )
            ->toArray();
    }

    private function parseTasks(Collection $tasks, Collection $tasksGroups): Collection
    {
        return $tasks->concat($tasksGroups)
            ->sort(
                static fn(Task|TaskGroup $a, Task|TaskGroup $b) => $a->getCreatedAt()->timestamp <=>
                    $b->getCreatedAt()->timestamp
            )
            ->values()
            ->map(fn(Task|TaskGroup $task) => $task->toViewModel());
    }

    public function findAll(UserId $userId): array
    {
        return $this->parseTasks(
            Task::with(
                [
                    'catalogs:id',
                    'files:id',
                    'bookmarks:id',
                    'notes:id',
                    'videos:id',
                    'subtasks' => static fn($query) => $query->orderBy('date', 'desc')
                ]
            )
                ->select('tasks.*', 'alarms.id as alarmId')
                ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
                ->where('tasks.user_id', '=', $userId)
                ->orderBy('created_at', 'desc')->get(),
            TaskGroup::with(
                [
                    'catalogs:id',
                    'files:id',
                    'bookmarks:id',
                    'notes:id',
                    'videos:id',
                    'tasks' => static fn($query) => $query->orderBy('date', 'desc')
                ]
            )
                ->select('tasks_groups.*', 'alarms_groups.id as alarmId')
                ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
                ->where('tasks_groups.user_id', '=', $userId)
                ->orderBy('created_at', 'desc')->get()
        )
            ->toArray();
    }
}
