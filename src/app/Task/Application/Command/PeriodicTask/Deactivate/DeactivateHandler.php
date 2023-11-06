<?php

namespace App\Task\Application\Command\PeriodicTask\Deactivate;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Domain\Event\PeriodicTask\AlarmDeactivated;
use App\Task\Domain\Event\PeriodicTask\Updated;
use App\Task\Domain\Event\SingleTask\TasksModified;
use App\Task\Domain\TaskStatus;

class DeactivateHandler extends ModifyTaskHandler
{
    public function handle(Deactivate $command): bool
    {
        $task = $this->getPeriodicTask($command->getTaskId());
        if (!$task->deactivate()) {
            return false;
        }
        $this->eventEmitter->emit(new Updated($task));
        if ($command->getAction() === DeactivateAction::NOT_MODIFY && $task->hasAlarm()) {
            $this->eventEmitter->emit(new AlarmDeactivated($task->getAlarmId(), $command->getAction()->value));

            return true;
        }
        if ($command->getAction() === DeactivateAction::DELETE && $task->hasAlarm()) {
            $this->eventEmitter->emit(new AlarmDeactivated($task->getAlarmId(), $command->getAction()->value));
        }

        $tasksInFuture = $task->getTasksInFuture();
        $ids = $tasksInFuture->getIds();
        if ($command->getAction() === DeactivateAction::DELETE) {
            $tasksInFuture->delete();
        }
        if ($command->getAction() === DeactivateAction::REJECT) {
            $rejectStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::REJECTED));
            $doneStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::DONE));
            $undoneStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::UNDONE));
            $tasksInFuture->reject($rejectStatus, $doneStatus, $undoneStatus);
            if ($task->hasAlarm()) {
                $this->eventEmitter->emit(new AlarmDeactivated($task->getAlarmId(), 'deactivate'));
            }
        }
        $this->eventEmitter->emit(new TasksModified($task->getUserId(), ...$ids));

        return true;
    }
}
