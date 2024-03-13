<?php

namespace App\Task\Application\Command\SingleTask\UpdateMainTask;

use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Command\SingleTask\ChangeStatus\ChangeStatus;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Domain\Event\SingleTask\TasksModified;
use App\Task\Domain\Event\SingleTask\Updated;
use App\Task\Domain\Service\StatusService;

class UpdateMainTaskHandler extends ModifyTaskHandler
{
    private StatusService $statusService;
    private SingleTask $task;
    private ?TaskId $newMainTaskId;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        StatusService $statusService
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->statusService = $statusService;
    }

    public function handle(UpdateMainTask $command): void
    {
        $this->task = $this->getSingleTask($command->getTaskId());
        $this->newMainTaskId = $command->getMainTask();
        $oldMainTaskId = $this->task->getMainTaskId();
        if (!$this->task->updateMainTask($this->newMainTaskId)) {
            return;
        }
        if ($oldMainTaskId !== null) {
            $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), $oldMainTaskId));
        }
        if ($this->newMainTaskId !== null) {
            $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), $this->newMainTaskId));
        }
        $this->modifyStatus();
        $this->eventEmitter->emit(new Updated($this->task));
    }

    private function modifyStatus(): void
    {
        if ($this->newMainTaskId === null) {
            return;
        }

        $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), $this->newMainTaskId));
        $newMainTask = $this->getSingleTask($this->newMainTaskId);
        $statusChange = $this->statusService->statusShouldBeChanged(
            $newMainTask->getStatus()->getName(),
            $this->task->getStatus()->getName(),
            $this->newMainTaskId,
            $this->task->getTaskId()
        );
        if ($statusChange === false) {
            return;
        }
        /** @var TaskStatus $status */
        $status = $this->queryBus->handle(new FindTaskStatusByName($statusChange->getStatus()));
        $this->commandBus->handle(new ChangeStatus($statusChange->getTaskId(), $status->getId()));
    }
}
