<?php

namespace App\Catalog\Application\Command\UpdateTasks;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\TasksAssigmentChanged;
use App\Catalog\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;

class UpdateTasksHandler extends ModifyCatalogHandler
{
    public function handle(UpdateTasks $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()));
        $catalog = $this->getCatalog($command->getCatalogId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            $changed = $catalog->updateTasksGroups(...$tasks->getTasksGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksGroupsAssigmentChanged($catalog->getUserId(), ...$changed->get()));
            }
        }
        if (!$tasks->getTasks()->isEmpty()) {
            $changed = $catalog->updateTasks(...$tasks->getTasks()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($catalog->getUserId(), ...$changed->get()));
            }
        }
    }
}
