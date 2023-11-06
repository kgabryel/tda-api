<?php

namespace App\Task\Application\Command\SingleTask\Create;

use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\DateService;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ChangeStatus\ChangeStatus;
use App\Task\Application\Query\FindById\FindById;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Application\TaskManagerInterface;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Event\AlarmsModified;
use App\Task\Domain\Event\Created;
use App\Task\Domain\Event\SingleTask\AlarmAdded;
use App\Task\Domain\Service\StatusService;
use App\Task\Domain\TaskStatus;
use DateTimeImmutable;

class CreateHandler extends AssignedUserCommandHandler
{
    private CommandBus $commandBus;
    private StatusService $statusService;
    private TaskManagerInterface $taskManager;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        StatusService $statusService,
        TaskManagerInterface $taskManager
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->commandBus = $commandBus;
        $this->statusService = $statusService;
        $this->taskManager = $taskManager;
    }

    public function handle(Create $command): void
    {
        $date = $command->getTask()->getDate();
        $status = $this->getStatus($date);
        $mainTaskId = $command->getTask()->getMainTask();
        if ($mainTaskId !== null) {
            $mainTask = $this->findTask($mainTaskId);
            /**
             * Sprawdza czy nie trzeba zmienic statusu nowo tworzonego zadania lub zadania glownego
             */
            $statusResult = $this->statusService->statusShouldBeChanged(
                $mainTask->getStatusName(),
                $status,
                $mainTaskId,
                $command->getTask()->getTaskId()
            );

            if ($statusResult !== false) {
                if ($statusResult->getTaskId()->getValue() === $mainTaskId->getValue()) {
                    $mainTaskStatus = $this->queryBus->handle(new FindTaskStatusByName($statusResult->getStatus()));
                    $this->commandBus->handle(new ChangeStatus($mainTaskId, $mainTaskStatus->getId()));
                } elseif ($statusResult->getTaskId()->getValue() === $command->getTask()->getTaskId()->getValue()) {
                    $status = $statusResult->getStatus();
                }
            }
        }
        $status = $this->queryBus->handle(new FindTaskStatusByName($status));
        $task = $this->taskManager->createSingleTask($command->getTask(), $this->userId, $status);
        $this->eventEmitter->emit(new Created($task));
        if ($command->getAlarm() !== null) {
            $task->setAlarmId($command->getAlarm()->getAlarm()->getAlarmId());
            $this->eventEmitter->emit(
                new AlarmAdded(
                    $command->getTask()->getTaskId(),
                    $command->getAlarm()->getAlarm(),
                    $command->getAlarm()->getNotifications()
                )
            );
            $this->eventEmitter->emit(
                new AlarmsModified($task->getUserId(), $command->getAlarm()->getAlarm()->getAlarmId())
            );
        }
    }

    private function getStatus(?DateTimeImmutable $date): TaskStatus
    {
        if ($date === null) {
            return TaskStatus::TO_DO;
        }
        if (DateService::toStartOfDay($date) >= DateService::toStartOfDay(new DateTimeImmutable())) {
            return TaskStatus::TO_DO;
        }

        return TaskStatus::UNDONE;
    }

    private function findTask(TaskId $id): SingleTask
    {
        return $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
    }
}
