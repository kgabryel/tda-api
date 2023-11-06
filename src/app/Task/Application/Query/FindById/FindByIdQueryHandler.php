<?php

namespace App\Task\Application\Query\FindById;

use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Query\TaskQueryHandler;
use App\Task\Application\Query\TaskType;
use App\Task\Application\ReadRepository;
use App\Task\Application\ViewModel\PeriodicTask as PeriodicTaskView;
use App\Task\Application\ViewModel\SingleTask as SingleTaskView;
use App\Task\Domain\Entity\PeriodicTask as PeriodicTaskEntity;
use App\Task\Domain\Entity\SingleTask as SingleTaskEntity;
use App\Task\Domain\Repository\TasksWriteRepository;

class FindByIdQueryHandler extends TaskQueryHandler
{
    private TasksWriteRepository $writeRepository;

    public function __construct(ReadRepository $repository, TasksWriteRepository $writeRepository)
    {
        parent::__construct($repository);
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindById $query): SingleTaskEntity|SingleTaskView|PeriodicTaskEntity|PeriodicTaskView
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->findDomainModel($query);
        }

        return $this->findViewModel($query);
    }

    private function findDomainModel(FindById $query): SingleTaskEntity|PeriodicTaskEntity
    {
        return match ($query->getTaskType()) {
            TaskType::SINGLE_TASK => $this->writeRepository->findSingleTaskById(
                $this->userId,
                new TaskId($query->getId())
            ),
            TaskType::PERIODIC_TASK => $this->writeRepository->findPeriodicTaskById(
                $this->userId,
                new TasksGroupId($query->getId())
            ),
            null => $this->writeRepository->findById($this->userId, $query->getId())
        };
    }

    private function findViewModel(FindById $query): SingleTaskView|PeriodicTaskView
    {
        return match ($query->getTaskType()) {
            TaskType::SINGLE_TASK => $this->readRepository->findSingleTaskById(
                $this->userId,
                new TaskId($query->getId())
            ),
            TaskType::PERIODIC_TASK => $this->readRepository->findPeriodicTaskById(
                $this->userId,
                new TasksGroupId($query->getId())
            ),
            null => $this->readRepository->findById($this->userId, $query->getId())
        };
    }
}
