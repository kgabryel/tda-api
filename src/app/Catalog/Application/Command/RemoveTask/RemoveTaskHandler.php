<?php

namespace App\Catalog\Application\Command\RemoveTask;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\TasksAssigmentChanged;
use App\Catalog\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;

class RemoveTaskHandler extends ModifyCatalogHandler
{
    public function handle(RemoveTask $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes($command->getTaskId()));
        $catalog = $this->getCatalog($command->getCatalogId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            if ($catalog->removeTasksGroup(new TasksGroupId($command->getTaskId()))) {
                $this->eventEmitter->emit(
                    new TasksGroupsAssigmentChanged($catalog->getUserId(), new TasksGroupId($command->getTaskId()))
                );
            }
        } elseif (!$tasks->getTasks()->isEmpty()) {
            if ($catalog->removeTask(new TaskId($command->getTaskId()))) {
                $this->eventEmitter->emit(
                    new TasksAssigmentChanged($catalog->getUserId(), new TaskId($command->getTaskId()))
                );
            }
        }
    }
}
