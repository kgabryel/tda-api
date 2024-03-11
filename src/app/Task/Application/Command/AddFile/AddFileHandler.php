<?php

namespace App\Task\Application\Command\AddFile;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\FilesAssigmentChanged;

class AddFileHandler extends ModifyTaskHandler
{
    public function handle(AddFile $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->addFile($command->getFileId())) {
            $this->eventEmitter->emit(new FilesAssigmentChanged($task->getUserId(), $command->getFileId()));
        }
    }
}
