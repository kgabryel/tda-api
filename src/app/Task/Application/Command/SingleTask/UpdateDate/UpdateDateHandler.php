<?php

namespace App\Task\Application\Command\SingleTask\UpdateDate;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Command\SingleTask\ChangeStatus\ChangeStatus;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Domain\Event\SingleTask\Updated;
use App\Task\Domain\TaskStatus;

class UpdateDateHandler extends ModifyTaskHandler
{
    public function handle(UpdateDate $command): void
    {
        $task = $this->getSingleTask($command->getTaskId());
        if (!$task->updateDate($command->getDate())) {
            return;
        }
        $this->eventEmitter->emit(new Updated($task));
        $date = $command->getDate();
        if ($date === null) {
            return;
        }

        if ($date->getTimestamp() > time()) {
            return;
        }
        $taskStatus = $task->getStatusName();
        if (in_array($taskStatus, [TaskStatus::DONE, TaskStatus::REJECTED, TaskStatus::UNDONE], true)) {
            return;
        }
        $undoneStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::UNDONE));
        $this->commandBus->handle(new ChangeStatus($task->getTaskId(), $undoneStatus->getId()));
    }
}
