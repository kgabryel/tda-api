<?php

namespace App\Task\Application\Command\RemoveBookmark;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\BookmarksAssigmentChanged;

class RemoveBookmarkHandler extends ModifyTaskHandler
{
    public function handle(RemoveBookmark $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->removeBookmark($command->getBookmarkId())) {
            $this->eventEmitter->emit(new BookmarksAssigmentChanged($task->getUserId(), $command->getBookmarkId()));
        }
    }
}
