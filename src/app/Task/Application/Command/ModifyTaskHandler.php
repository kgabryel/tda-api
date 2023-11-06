<?php

namespace App\Task\Application\Command;

use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Query\FindById\FindById;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\Task;

abstract class ModifyTaskHandler extends CommandHandler
{
    protected CommandBus $commandBus;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, CommandBus $commandBus)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->commandBus = $commandBus;
    }

    protected function getTask(string $taskId): Task
    {
        return $this->queryBus->handle(new FindById($taskId, QueryResult::DOMAIN_MODEL));
    }

    protected function getSingleTask(TaskId $taskId): SingleTask
    {
        return $this->queryBus->handle(
            new FindById($taskId, QueryResult::DOMAIN_MODEL)
        );
    }

    protected function getPeriodicTask(TasksGroupId $taskId): PeriodicTask
    {
        return $this->queryBus->handle(
            new FindById($taskId, QueryResult::DOMAIN_MODEL)
        );
    }
}
