<?php

namespace App\Task\Application\Command\RemoveCatalog;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\CatalogsAssigmentChanged;

class RemoveCatalogHandler extends ModifyTaskHandler
{
    public function handle(RemoveCatalog $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->removeCatalog($command->getCatalogId())) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($task->getUserId(), $command->getCatalogId()));
        }
    }
}
