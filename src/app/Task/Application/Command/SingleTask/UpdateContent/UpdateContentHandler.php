<?php

namespace App\Task\Application\Command\SingleTask\UpdateContent;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\SingleTask\Updated;

class UpdateContentHandler extends ModifyTaskHandler
{
    public function handle(UpdateContent $command): void
    {
        $task = $this->getSingleTask($command->getTaskId());
        if ($task->updateContent($command->getContent())) {
            $this->eventEmitter->emit(new Updated($task));
        }
    }
}
