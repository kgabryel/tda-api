<?php

namespace App\Bookmark\Application\Command\UpdateTasks;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\TasksAssigmentChanged;
use App\Bookmark\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;

class UpdateTasksHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateTasks $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()));
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            $changed = $bookmark->updateTasksGroups(...$tasks->getTasksGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksGroupsAssigmentChanged($bookmark->getUserId(), ...$changed->get()));
            }
        }
        if (!$tasks->getTasks()->isEmpty()) {
            $changed = $bookmark->updateTasks(...$tasks->getTasks()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($bookmark->getUserId(), ...$changed->get()));
            }
        }
    }
}
