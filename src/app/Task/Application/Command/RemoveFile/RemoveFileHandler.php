<?php

namespace App\Task\Application\Command\RemoveFile;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\FilesAssigmentChanged;

class RemoveFileHandler extends ModifyTaskHandler
{
    public function handle(RemoveFile $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->removeFile($command->getFileId())) {
            $this->eventEmitter->emit(new FilesAssigmentChanged($task->getUserId(), $command->getFileId()));
        }
    }
}
