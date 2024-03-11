<?php

namespace App\Task\Application\Command\RemoveVideo;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\VideosAssigmentChanged;

class RemoveVideoHandler extends ModifyTaskHandler
{
    public function handle(RemoveVideo $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->removeVideo($command->getVideoId())) {
            $this->eventEmitter->emit(new VideosAssigmentChanged($task->getUserId(), $command->getVideoId()));
        }
    }
}
