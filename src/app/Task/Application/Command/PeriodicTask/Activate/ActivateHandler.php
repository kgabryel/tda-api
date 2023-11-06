<?php

namespace App\Task\Application\Command\PeriodicTask\Activate;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Application\Command\SingleTask\Create\Create;
use App\Task\Application\Command\SingleTask\Create\TaskDto;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\TasksInFuture;
use App\Task\Domain\Event\PeriodicTask\AlarmActivated;
use App\Task\Domain\Event\PeriodicTask\Updated;
use App\Task\Domain\Event\SingleTask\TasksModified;
use App\Task\Domain\TaskStatus;
use DateTimeImmutable;

class ActivateHandler extends ModifyTaskHandler
{
    private TasksInFuture $tasksInFuture;
    private Activate $command;
    private PeriodicTask $task;
    private UuidInterface $uuid;
    private array $tasks;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UuidInterface $uuid,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->uuid = $uuid;
    }

    private function activeWithAlarm(): void
    {
        if ($this->command->getAction() === ActivateAction::NOT_MODIFY) {
            $this->eventEmitter->emit(new Updated($this->task));
            $this->eventEmitter->emit(
                new AlarmActivated($this->task->getAlarmId(), $this->command->getAction()->value)
            );

            return;
        }
        $toDoStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::TO_DO));
        $this->tasksInFuture->activate($toDoStatus);
        $dateService = DateService::get(new DateTimeImmutable(), DateService::getNextMonthEnd(), $this->task);
        $tasks = new SingleTasksIdsList();
        $taskGroup = new TasksGroupsList($this->task->getTaskId());
        $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        while ($currentDate !== false) {
            $singleTask = $this->findTask($currentDate);
            if ($singleTask === null) {
                $id = new TaskId($this->uuid->getValue());
                $tasks->add($id);
                $this->commandBus->handle(new Create(TaskDto::fromPeriodicTask($this->task, $id, $currentDate)));
                $taskGroup->addGroup($id, new AlarmId($this->uuid->getValue()), $currentDate->getTimestamp());
            } else {
                $tasks->add($singleTask->getTaskId());
                $alarmId = $singleTask->getAlarmId() ?? new AlarmId($this->uuid->getValue());
                $singleTask->setAlarmId($alarmId, true);
                $taskGroup->addGroup($singleTask->getTaskId(), $alarmId, $currentDate->getTimestamp());
            }
            $dateService->setNext();
            $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        }
        $this->eventEmitter->emit(
            new AlarmActivated($this->task->getAlarmId(), $this->command->getAction()->value, $taskGroup)
        );
        $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), ...$tasks->get()));
    }

    public function handle(Activate $command): bool
    {
        $this->command = $command;
        $this->task = $this->getPeriodicTask($command->getTaskId());
        if (!$this->task->activate()) {
            return false;
        }
        $this->eventEmitter->emit(new Updated($this->task));

        if ($command->getAction() === ActivateAction::NOT_MODIFY) {
            $alarmId = $this->task->getAlarmId();
            if ($alarmId === null) {
                return true;
            }
        }
        $this->tasksInFuture = $this->task->getTasksInFuture();
        $this->tasks = $this->tasksInFuture->get();
        if (!$this->task->hasAlarm()) {
            $this->activeWithoutAlarm();
        } else {
            $this->activeWithAlarm();
        }

        return true;
    }

    private function activeWithoutAlarm(): void
    {
        if ($this->command->getAction() === ActivateAction::NOT_MODIFY) {
            $this->eventEmitter->emit(new Updated($this->task));

            return;
        }
        $toDoStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::TO_DO));
        $this->tasksInFuture->activate($toDoStatus);
        $dateService = DateService::get(new DateTimeImmutable(), DateService::getNextMonthEnd(), $this->task);
        $tasks = new SingleTasksIdsList();
        $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        while ($currentDate !== false) {
            $singleTask = $this->findTask($currentDate);
            if ($singleTask === null) {
                $id = new TaskId($this->uuid->getValue());
                $tasks->add($id);
                $this->commandBus->handle(new Create(TaskDto::fromPeriodicTask($this->task, $id, $currentDate)));
            } else {
                $tasks->add($singleTask->getTaskId());
            }
            $dateService->setNext();
            $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        }
        $this->eventEmitter->emit(new TasksModified($this->task->getUserId(), ...$tasks->get()));
    }

    private function findTask(DateTimeImmutable $date): ?SingleTask
    {
        /** @var SingleTask $task */
        foreach ($this->tasks as $task) {
            if ($task->getDate()?->getTimestamp() === $date->getTimestamp()) {
                return $task;
            }
        }

        return null;
    }
}
