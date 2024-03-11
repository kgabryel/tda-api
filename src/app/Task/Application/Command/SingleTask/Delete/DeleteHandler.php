<?php

namespace App\Task\Application\Command\SingleTask\Delete;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Event\AlarmDeleted;
use App\Task\Domain\Event\AlarmDetachedFromTask;
use App\Task\Domain\Event\AlarmsModified;
use App\Task\Domain\Event\PeriodicTask\TasksGroupsModified;
use App\Task\Domain\Event\Removed;
use App\Task\Domain\Event\SingleTask\Deleted;
use App\Task\Domain\Event\SingleTask\TasksModified;

class DeleteHandler extends ModifyTaskHandler
{
    private Delete $command;
    private SingleTask $task;

    public function handle(Delete $command): void
    {
        $this->command = $command;
        $this->task = $this->getSingleTask($command->getTaskId());
        if (!$this->task->delete()) {
            return;
        }
        $taskGroupId = $this->task->getTaskGroupId();
        $alarmId = $this->task->getAlarmId();
        $mainTaskId = $this->task->getMainTaskId();
        if ($taskGroupId !== null) {
            $this->eventEmitter->emit(new TasksGroupsModified($this->task->getUserId(), $taskGroupId));
        }
        if ($mainTaskId === null) {
            $this->modifySubtasks();
        }
        if ($alarmId !== null) {
            $this->modifyAlarm();
        }
        $this->eventEmitter->emit(new Removed($this->task));
        $this->eventEmitter->emit(new Deleted($this->task->getTaskId()));
    }

    private function modifySubtasks(): void
    {
        $subtasks = $this->task->getSubtasksIds();
        if ($this->command->deleteSubtasks()) {
            $this->task->deleteSubtasks();
        } else {
            $this->task->disconnectSubtasks();
        }
        $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), ...$subtasks));
    }

    private function modifyAlarm(): void
    {
        $alarmId = $this->task->getAlarmId();
        if ($this->command->deleteAlarm()) {
            $this->eventEmitter->emit(new AlarmDeleted($alarmId));
        } else {
            $this->eventEmitter->emit(new AlarmDetachedFromTask($alarmId));
        }
        $this->eventEmitter->emit(new AlarmsModified($this->task->getUserId(), $alarmId));
    }
}
