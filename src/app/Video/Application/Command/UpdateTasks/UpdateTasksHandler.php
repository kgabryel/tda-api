<?php

namespace App\Video\Application\Command\UpdateTasks;

use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\TasksAssigmentChanged;
use App\Video\Domain\Event\TasksGroupsAssigmentChanged;

class UpdateTasksHandler extends ModifyVideoHandler
{
    public function handle(UpdateTasks $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()));
        $video = $this->getVideo($command->getVideoId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            $changed = $video->updateTasksGroups(...$tasks->getTasksGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksGroupsAssigmentChanged($video->getUserId(), ...$changed->get()));
            }
        }
        if (!$tasks->getTasks()->isEmpty()) {
            $changed = $video->updateTasks(...$tasks->getTasks()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($video->getUserId(), ...$changed->get()));
            }
        }
    }
}
