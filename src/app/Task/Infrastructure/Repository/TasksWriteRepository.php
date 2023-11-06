<?php

namespace App\Task\Infrastructure\Repository;

use App\Core\Cache;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Repository\TasksWriteRepository as TasksWriteRepositoryInterface;
use App\Task\Domain\TaskStatus;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use App\Task\Infrastructure\TaskManager;
use Carbon\Carbon;
use DateTimeImmutable;

class TasksWriteRepository implements TasksWriteRepositoryInterface
{
    public function findSingleTaskById(UserId $userId, TaskId $taskId): SingleTask
    {
        $tId = $taskId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(TaskManager::getCacheKey($taskId), static function () use ($tId, $uId) {
            return Task::select('tasks.*', 'tasks_statuses.name as statusName', 'alarms.id as alarmId')
                ->join('tasks_statuses', 'tasks_statuses.id', '=', 'tasks.status_id')
                ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
                ->where('tasks.id', '=', $tId)
                ->where('tasks.user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }

    public function findPeriodicTaskById(UserId $userId, TasksGroupId $taskId): PeriodicTask
    {
        $tId = $taskId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(TaskManager::getCacheKey($taskId), static function () use ($tId, $uId) {
            return TaskGroup::select('tasks_groups.*', 'alarms_groups.id as alarmId')
                ->where('tasks_groups.id', '=', $tId)
                ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
                ->where('tasks_groups.user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }

    public function findById(UserId $userId, string $taskId): SingleTask|PeriodicTask
    {
        $uId = $userId->getValue();

        return Cache::remember(TaskManager::getCacheKey($taskId), static function () use ($taskId, $uId) {
            $task = Task::select('tasks.*', 'tasks_statuses.name as statusName', 'alarms.id as alarmId')
                ->join('tasks_statuses', 'tasks_statuses.id', '=', 'tasks.status_id')
                ->leftJoin('alarms', 'tasks.id', '=', 'alarms.task_id')
                ->where('tasks.id', '=', $taskId)
                ->where('tasks.user_id', '=', $uId)
                ->first();
            if ($task === null) {
                $task = TaskGroup::select('tasks_groups.*', 'alarms_groups.id as alarmId')
                    ->where('tasks_groups.id', '=', $taskId)
                    ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
                    ->where('tasks_groups.user_id', '=', $uId)
                    ->firstOrFail();
            }

            return $task->toDomainModel();
        });
    }

    public function getTasksToDisable(): array
    {
        return Task::whereDate('tasks.date', '<=', Carbon::now()->addDays(-1))
            ->join('tasks_statuses', 'tasks_statuses.id', '=', 'tasks.status_id')
            ->whereNotIn(
                'tasks_statuses.name',
                [TaskStatus::UNDONE->value, TaskStatus::DONE->value, TaskStatus::REJECTED->value]
            )
            ->select('tasks.id', 'tasks.user_id')
            ->get()
            ->toArray();
    }

    public function findTasksToCreate(DateTimeImmutable $date): array
    {
        return TaskGroup::select('tasks_groups.*', 'alarms_groups.id as alarmId')
            ->where('tasks_groups.active', '=', true)
            ->where(function ($query) use ($date) {
                $query->whereNull('tasks_groups.stop')->orWhere('tasks_groups.stop', '>=', $date);
            })
            ->leftJoin('alarms_groups', 'tasks_groups.id', '=', 'alarms_groups.task_id')
            ->get()
            ->map(static fn(TaskGroup $taskGroup) => $taskGroup->toDomainModel())
            ->toArray();
    }
}
