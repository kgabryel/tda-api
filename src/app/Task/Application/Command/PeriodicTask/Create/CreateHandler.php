<?php

namespace App\Task\Application\Command\PeriodicTask\Create;

use App\Alarm\Application\Command\PeriodicAlarm\Create\AlarmDto as AlarmDtoAlarm;
use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\DateService;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\Create\Create as CreateSingleTask;
use App\Task\Application\Command\SingleTask\Create\TaskDto as SingleTaskDto;
use App\Task\Application\TaskManagerInterface;
use App\Task\Domain\Event\Created;
use App\Task\Domain\Event\PeriodicTask\PeriodicAlarmAdded;
use App\Task\Domain\Event\SingleTask\TasksModified;

class CreateHandler extends AssignedUserCommandHandler
{
    private TaskManagerInterface $taskManager;
    private UuidInterface $uuid;
    private CommandBus $commandBus;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        TaskManagerInterface $taskManager,
        UuidInterface $uuid,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->taskManager = $taskManager;
        $this->uuid = $uuid;
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
    }

    public function handle(Create $command): void
    {
        $alarmDto = $command->getAlarm();
        $taskDto = $command->getTask();
        /**
         * lista dat, dla ktorych maja zostac utworzone zadania pojedyncze, maksymalnie do konca nastepnego miesiaca
         */
        $dates = DateService::getDatesInRange(
            $taskDto->getStart(),
            DateService::getNextNthMonthEnd(1),
            $taskDto->getInterval(),
            $taskDto->getIntervalType(),
            null,
            $taskDto->getStop()
        );
        $taskGroup = $this->taskManager->createPeriodicTask($taskDto, $this->userId);
        $taskGroupDto = $alarmDto === null ? null : new TasksGroupsList($taskGroup->getTaskId());
        $tasks = new SingleTasksIdsList();
        foreach ($dates->get() as $date) {
            $taskId = new TaskId($this->uuid->getValue());
            $taskGroupDto?->addGroup($taskId, new AlarmId($this->uuid->getValue()), $date->getTimestamp());
            $tasks->add($taskId);
            $singleTaskDto = SingleTaskDto::get($taskDto->getName(), $taskDto->getContent(), $taskId, $date);
            $singleTaskDto->setTasksGroupId($taskGroup->getTaskId());
            $this->commandBus->handle(new CreateSingleTask($singleTaskDto));
        }
        if ($alarmDto !== null && $taskGroupDto !== null) {
            $a = new AlarmDtoAlarm(
                new AlarmsGroupId($this->uuid->getValue()),
                $alarmDto->getName(),
                $alarmDto->getContent(),
                $alarmDto->getCatalogsList(),
                $taskDto->getStart(),
                $taskDto->getStop(),
                $taskDto->getInterval(),
                $taskDto->getIntervalType()
            );
            $this->eventEmitter->emit(
                new PeriodicAlarmAdded($a, $alarmDto->getNotifications(), $taskGroupDto)
            );
        }
        $this->eventEmitter->emit(new Created($taskGroup));
        $this->eventEmitter->emit(new TasksModified($taskGroup->getUserId(), ... $tasks->get()));
    }
}
