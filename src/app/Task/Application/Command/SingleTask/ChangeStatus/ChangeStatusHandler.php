<?php

namespace App\Task\Application\Command\SingleTask\ChangeStatus;

use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Query\FindTaskStatusById\FindTaskStatusById;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\StatusId;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Domain\Event\SingleTask\AlarmChecked;
use App\Task\Domain\Event\SingleTask\TasksModified;
use App\Task\Domain\Event\SingleTask\Updated;
use App\Task\Domain\TaskStatus as TaskStatusName;

class ChangeStatusHandler extends ModifyTaskHandler
{
    public function handle(ChangeStatus $command): void
    {
        $task = $this->getSingleTask($command->getTaskId());
        $status = $this->getStatus($command->getStatusId());
        $result = $task->changeStatus($status);
        if ($result === false) {
            return;
        }
        $alarms = new SingleAlarmsIdsList();
        $checkAlarms = $status->getName() === TaskStatusName::DONE || $status->getName() === TaskStatusName::REJECTED;
        $alarmId = $task->getAlarmId();
        if (is_array($result)) {
            /** @var SingleTask $subtask */
            foreach ($result as $subtask) {
                if ($checkAlarms && $alarmId !== null) {
                    /** @var AlarmId $subtaskAlarmId */
                    $subtaskAlarmId = $subtask->getAlarmId();
                    $alarms->add($subtaskAlarmId);
                }
            }
            $this->eventEmitter->emit(new TasksModified($task->getUserId(), ...$result));
        }

        if ($alarmId !== null && $checkAlarms) {
            $alarms->add($alarmId);
        }
        if (!$alarms->isEmpty()) {
            foreach ($alarms->get() as $alarm) {
                $this->eventEmitter->emit(new AlarmChecked($alarm));
            }
        }
        $this->eventEmitter->emit(new Updated($task));
    }

    public function getStatus(StatusId $statusId): TaskStatus
    {
        return $this->queryBus->handle(new FindTaskStatusById($statusId));
    }
}
