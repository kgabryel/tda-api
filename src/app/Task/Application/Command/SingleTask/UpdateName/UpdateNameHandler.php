<?php

namespace App\Task\Application\Command\SingleTask\UpdateName;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\SingleTask\Updated;

class UpdateNameHandler extends ModifyTaskHandler
{
    public function handle(UpdateName $command): void
    {
        $task = $this->getSingleTask($command->getTaskId());
        if ($task->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($task));
        }
    }
}
