<?php

namespace App\Task\Application\Command\PeriodicTask\CreateTasksForPeriodicTask;

use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Command\SingleTask\Create\Create as CreateSingleTask;
use App\Task\Application\Command\SingleTask\Create\TaskDto as SingleTaskDto;
use App\Task\Domain\Event\SingleTask\TasksModified;

class CreateTasksForPeriodicTaskHandler extends ModifyTaskHandler
{
    public function handle(CreateTasksForPeriodicTask $command): void
    {
        $tasks = new SingleTasksIdsList();
        foreach ($command->getDates()->get() as $date) {
            $taskGroup = $command->getGroups()->getByTime($date->getTimestamp());
            $taskId = $taskGroup->getTaskId();
            $tasks->add($taskId);
            $singleTaskDto = SingleTaskDto::get($command->getName(), $command->getContent(), $taskId, $date);
            $singleTaskDto->setTasksGroupId($command->getTaskId());
            $this->commandBus->handle(new CreateSingleTask($singleTaskDto));
        }
        $this->eventEmitter->emit(new TasksModified($command->getUserId(), ... $tasks->get()));
    }
}
