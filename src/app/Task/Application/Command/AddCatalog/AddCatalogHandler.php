<?php

namespace App\Task\Application\Command\AddCatalog;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\CatalogsAssigmentChanged;

class AddCatalogHandler extends ModifyTaskHandler
{
    public function handle(AddCatalog $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->addCatalog($command->getCatalogId())) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($task->getUserId(), $command->getCatalogId()));
        }
    }
}
