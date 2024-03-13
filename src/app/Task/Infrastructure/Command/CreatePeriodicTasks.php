<?php

namespace App\Task\Infrastructure\Command;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\BusUtils;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\PeriodicTask\CreateTasksForPeriodicTask\CreateTasksForPeriodicTask;
use App\Task\Application\Command\SingleTask\Create\Create;
use App\Task\Application\Command\SingleTask\Create\TaskDto;
use App\Task\Application\Query\FindTasksToCreate\FindTasksToCreate;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Event\PeriodicTask\AlarmsAdded;
use App\Task\Domain\Event\SingleTask\TasksModified;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreatePeriodicTasks extends Command
{
    protected $signature = 'tasks:periodic-create';
    private CommandBus $commandBus;
    private UuidInterface $uuid;
    private EventEmitter $eventEmitter;
    private QueryBus $queryBus;
    private BusUtils $busUtils;

    public function __construct(
        CommandBus $commandBus,
        UuidInterface $uuid,
        EventEmitter $eventEmitter,
        QueryBus $queryBus,
        BusUtils $busUtils
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->uuid = $uuid;
        $this->eventEmitter = $eventEmitter;
        $this->queryBus = $queryBus;
        $this->busUtils = $busUtils;
    }

    public function handle(): int
    {
        $cronId = $this->uuid->getValue();
        $start = DateService::getNextNthMonthStart(2);
        $tasks = $this->queryBus->handle(new FindTasksToCreate(Carbon::instance($start)->toDateTimeImmutable()));
        Log::info('tasks:periodic-create', [
            'id' => $cronId,
            'tasks' => array_map(static fn(PeriodicTask $task) => $task->getTaskId(), $tasks)
        ]);
        /** @var PeriodicTask $taskGroup */
        foreach ($tasks as $taskGroup) {
            $this->busUtils->setUserId($taskGroup->getUserId());
            $dates = DateService::getDatesInRange(
                $start,
                DateService::getNextNthMonthEnd(2),
                $taskGroup->getInterval(),
                $taskGroup->getIntervalType(),
                $taskGroup->getStart(),
                $taskGroup->getStop()
            );
            $alarmsGroupId = $taskGroup->getAlarmId();
            if ($alarmsGroupId === null) {
                $tasks = new SingleTasksIdsList();
                foreach ($dates->get() as $date) {
                    $taskId = new TaskId($this->uuid->getValue());
                    Log::info('tasks:periodic-create', [
                        'id' => $cronId,
                        'type' => 'withoutAlarm',
                        'task' => $taskId
                    ]);
                    $tasks->add($taskId);
                    $this->commandBus->handle(new Create(TaskDto::fromPeriodicTask($taskGroup, $taskId, $date)));
                }
                $this->eventEmitter->emit(new TasksModified($taskGroup->getUserId(), ...$tasks->get()));
            } else {
                $groups = new TasksGroupsList($taskGroup->getTaskId());
                /** @var Carbon $date */
                foreach ($dates->get() as $date) {
                    $taskId = new TaskId($this->uuid->getValue());
                    $alarmId = new AlarmId($this->uuid->getValue());
                    Log::info('tasks:periodic-create', [
                        'id' => $cronId,
                        'type' => 'withAlarm',
                        'task' => $taskId,
                        'alarm' => $alarmId
                    ]);
                    $groups->addGroup(
                        $taskId,
                        $alarmId,
                        $date->getTimestamp()
                    );
                }
                $this->commandBus->handle(
                    new CreateTasksForPeriodicTask(
                        $dates,
                        $taskGroup->getUserId(),
                        $taskGroup->getTaskId(),
                        $taskGroup->getName(),
                        $taskGroup->getContent(),
                        $groups
                    )
                );
                $this->eventEmitter->emit(new AlarmsAdded($alarmsGroupId, $groups, $dates));
            }
        }
        Log::info('tasks:periodic-create', [
            'id' => $cronId,
            'finish' => true
        ]);

        return 0;
    }
}
