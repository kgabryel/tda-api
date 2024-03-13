<?php

namespace App\Task\Application\Command\PeriodicTask\Delete;

use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Event\AlarmsGroupsModified;
use App\Task\Domain\Event\PeriodicTask\Deleted;
use App\Task\Domain\Event\PeriodicTask\PeriodicAlarmDeleted;
use App\Task\Domain\Event\PeriodicTask\PeriodicAlarmDetachedFromTask;
use App\Task\Domain\Event\Removed;
use App\Task\Domain\Event\SingleTask\TasksModified;

class DeleteHandler extends ModifyTaskHandler
{
    private Delete $command;
    private PeriodicTask $task;

    public function handle(Delete $command): void
    {
        $this->command = $command;
        $this->task = $this->getPeriodicTask($command->getTaskId());
        $alarmId = $this->task->getAlarmId();
        if ($this->task->delete()) {
            if ($alarmId !== null) {
                $this->modifyAlarm($alarmId);
            }
            $this->modifyTasks();
            $this->eventEmitter->emit(new Removed($this->task));
            $this->eventEmitter->emit(new Deleted($this->task->getTaskId()));
        }
    }

    private function modifyAlarm(AlarmsGroupId $alarmId): void
    {
        if ($this->command->deleteAlarm()) {
            $this->eventEmitter->emit(new PeriodicAlarmDeleted($alarmId));
        } else {
            $this->eventEmitter->emit(new PeriodicAlarmDetachedFromTask($alarmId));
        }
        $this->eventEmitter->emit(new AlarmsGroupsModified($this->task->getUserId(), $alarmId));
    }

    private function modifyTasks(): void
    {
        $tasks = $this->task->getTasksIds();
        if ($this->command->deleteTasks()) {
            $this->task->deleteTasks();
        } else {
            $this->task->disconnectTasks();
        }
        $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), ...$tasks));
    }
}
