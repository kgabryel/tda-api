<?php

namespace App\Task\Infrastructure\Repository;

use App\Core\Cache;
use App\Task\Application\TasksStatusesRepository as TasksStatusesReadRepositoryInterface;
use App\Task\Domain\Entity\StatusId;
use App\Task\Domain\Entity\TaskStatus as DomainModel;
use App\Task\Domain\Repository\TasksStatusesWriteRepository;
use App\Task\Domain\TaskStatus as TaskStatusName;
use App\Task\Infrastructure\Model\TaskStatus;

class TasksStatusesRepository implements TasksStatusesReadRepositoryInterface, TasksStatusesWriteRepository
{
    public function exists(TaskStatusName $name): bool
    {
        return TaskStatus::where('name', '=', $name->value)->exists();
    }

    public function findAll(): array
    {
        return TaskStatus::orderBy('status_order', 'asc')->get()->map(
            static fn(TaskStatus $status) => $status->toViewModel()
        )
            ->toArray();
    }

    public function findById(StatusId $statusId): DomainModel
    {
        $sId = $statusId->getValue();

        return Cache::remember(self::getCacheKeyWithId($statusId), static function() use ($sId) {
            return TaskStatus::where('id', '=', $sId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }

    public static function getCacheKeyWithId(StatusId $statusId): string
    {
        return sprintf('tasks-statuses-%s', $statusId);
    }

    public function findByName(TaskStatusName $name): DomainModel
    {
        $sName = $name->value;
        $taskStatus = Cache::remember(sprintf('tasks-statuses-%s', $name->value), static function() use ($sName) {
            return TaskStatus::where('name', '=', $sName)
                ->firstOrFail()
                ->toDomainModel();
        });
        $key = self::getCacheKeyWithId($taskStatus->getId());
        Cache::add($key, $taskStatus);

        return Cache::get($key);
    }
}
