<?php

namespace App\Task\Infrastructure;

use App\Core\Cache;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Application\Command\PeriodicTask\Create\TaskDto as PeriodicTaskDto;
use App\Task\Application\Command\SingleTask\Create\TaskDto;
use App\Task\Application\TaskManagerInterface;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;

class TaskManager implements TaskManagerInterface
{
    public function deleteSingleTask(TaskId $taskId): void
    {
        $this->getSingleTask($taskId)->delete();
        Cache::forget(self::getCacheKey($taskId));
    }

    public function getSingleTask(TaskId $taskId): Task
    {
        $task = new Task();
        $task->id = $taskId->getValue();
        $task->exists = true;

        return $task;
    }

    public static function getCacheKey(TaskId|TasksGroupId|string $taskId): string
    {
        return sprintf('tasks-%s', $taskId);
    }

    public function updateSingleTask(SingleTask $task): void
    {
        $this->getSingleTask($task->getTaskId())
            ->changeStatusId($task->getStatus()->getId())
            ->setName($task->getName())
            ->setContent($task->getContent())
            ->setDate($task->getDate())
            ->setMainTask($task->getMainTaskId())
            ->update();
    }

    public function deletePeriodicTask(TasksGroupId $taskId): void
    {
        $this->getPeriodicTask($taskId)->delete();
        Cache::forget(self::getCacheKey($taskId));
    }

    public function getPeriodicTask(TasksGroupId $taskId): TaskGroup
    {
        $task = new TaskGroup();
        $task->id = $taskId->getValue();
        $task->exists = true;

        return $task;
    }

    public function createSingleTask(TaskDto $taskDto, UserId $userId, TaskStatus $taskStatus): SingleTask
    {
        $task = new Task();
        $task->setId($taskDto->getTaskId())
            ->setName($taskDto->getName())
            ->setContent($taskDto->getContent())
            ->setDate($taskDto->getDate())
            ->setStatusId($taskStatus->getId())
            ->setUserId($userId);
        if ($taskDto->getMainTask() !== null) {
            $task->setMainTaskId($taskDto->getMainTask());
        }
        if ($taskDto->getTasksGroupId() !== null) {
            $task->setGroupId($taskDto->getTasksGroupId());
        }
        $task->save();
        $task->setStatusName($taskStatus->getName()->value);
        $task->catalogs()->attach($taskDto->getCatalogsList()->getIds());
        $task->notes()->attach($taskDto->getNotesList()->getIds());
        $task->files()->attach($taskDto->getFilesList()->getIds());
        $task->bookmarks()->attach($taskDto->getBookmarksList()->getIds());
        $task->videos()->attach($taskDto->getVideosList()->getIds());
        $domainModel = $task->toDomainModel();
        $key = self::getCacheKey($domainModel->getTaskId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public function createPeriodicTask(PeriodicTaskDto $taskDto, UserId $userId): PeriodicTask
    {
        $taskGroup = new TaskGroup();
        $taskGroup->setId($taskDto->getTaskId())
            ->setName($taskDto->getName())
            ->setContent($taskDto->getContent())
            ->setUserId($userId)
            ->setStart($taskDto->getStart())
            ->setStop($taskDto->getStop())
            ->setInterval($taskDto->getInterval())
            ->setIntervalType($taskDto->getIntervalType()->value)
            ->setActiveValue($taskDto->getStop() === null || $taskDto->getStop()->getTimestamp() > time());
        $taskGroup->save();
        $taskGroup->catalogs()->attach($taskDto->getCatalogsList()->getIds());
        $taskGroup->notes()->attach($taskDto->getNotesList()->getIds());
        $taskGroup->files()->attach($taskDto->getFilesList()->getIds());
        $taskGroup->bookmarks()->attach($taskDto->getBookmarksList()->getIds());
        $taskGroup->videos()->attach($taskDto->getVideosList()->getIds());

        $domainModel = $taskGroup->toDomainModel();
        $key = self::getCacheKey($domainModel->getTaskId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public function updatePeriodicTask(PeriodicTask $task): void
    {
        $this->getPeriodicTask($task->getTaskId())
            ->setName($task->getName())
            ->setContent($task->getContent())
            ->setActiveValue($task->isActive())
            ->update();
    }
}
