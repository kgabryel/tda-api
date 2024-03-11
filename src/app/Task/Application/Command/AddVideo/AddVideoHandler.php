<?php

namespace App\Task\Application\Command\AddVideo;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\VideosAssigmentChanged;

class AddVideoHandler extends ModifyTaskHandler
{
    public function handle(AddVideo $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->addVideo($command->getVideoId())) {
            $this->eventEmitter->emit(new VideosAssigmentChanged($task->getUserId(), $command->getVideoId()));
        }
    }
}
