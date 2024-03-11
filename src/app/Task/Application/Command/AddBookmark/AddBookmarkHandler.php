<?php

namespace App\Task\Application\Command\AddBookmark;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\BookmarksAssigmentChanged;

class AddBookmarkHandler extends ModifyTaskHandler
{
    public function handle(AddBookmark $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->addBookmark($command->getBookmarkId())) {
            $this->eventEmitter->emit(new BookmarksAssigmentChanged($task->getUserId(), $command->getBookmarkId()));
        }
    }
}
